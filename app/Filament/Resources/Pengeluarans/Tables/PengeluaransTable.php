<?php

namespace App\Filament\Resources\Pengeluarans\Tables;

use App\Models\Expo;
use App\Models\Pengeluaran;
use App\Models\RekeningTujuan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PengeluaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—')
                    ->description(fn (Pengeluaran $record): ?string => $record->expo?->labelDetails()),

                TextColumn::make('nama_pengeluaran')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pengeluaran')
                    ->description(fn (Pengeluaran $record): ?string => $record->keterangan
                        ? \Illuminate\Support\Str::limit($record->keterangan, 60)
                        : null),

                TextColumn::make('nominal')
                    ->sortable()
                    ->label('Nominal')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('rekeningTujuan.nama_bank')
                    ->label('Sumber Dana')
                    ->sortable()
                    ->toggleable()
                    ->description(fn (Pengeluaran $record): ?string => $record->rekeningTujuan
                        ? $record->rekeningTujuan->nomor_rekening
                        : null),

                TextColumn::make('rek_transfer')
                    ->label('Rek. Penerima')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—')
                    ->description(fn (Pengeluaran $record): ?string => $record->nama_rekening_penerima),

                TextColumn::make('nama_rekening_penerima')
                    ->label('Nama Penerima')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->searchable()
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),

                TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),

                IconColumn::make('bukti_transfer')
                    ->label('Bukti')
                    ->boolean()
                    ->getStateUsing(fn (Pengeluaran $record): bool => filled($record->bukti_transfer))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-exclamation-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderByRaw('bukti_transfer IS NULL '.$direction);
                    }),

                ImageColumn::make('bukti_preview')
                    ->label('Preview')
                    ->getStateUsing(function (Pengeluaran $record): ?string {
                        $path = $record->bukti_transfer;
                        if (! filled($path)) {
                            return null;
                        }

                        return str_ends_with(strtolower((string) $path), '.pdf') ? null : $path;
                    })
                    ->square()
                    ->size(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),

                SelectFilter::make('expo_id')
                    ->label('Expo')
                    ->relationship(
                        name: 'expo',
                        titleAttribute: 'nama_expo',
                        modifyQueryUsing: fn (Builder $query) => $query->orderByDesc('tanggal_mulai'),
                    )
                    ->getOptionLabelFromRecordUsing(fn (Expo $record) => $record->labelForSelect())
                    ->searchable()
                    ->preload(),

                SelectFilter::make('rekening_tujuan_id')
                    ->label('Sumber Dana')
                    ->relationship('rekeningTujuan', 'nama_bank')
                    ->getOptionLabelFromRecordUsing(
                        fn (RekeningTujuan $record) => $record->nama_bank.' - '.$record->nomor_rekening
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('bukti_transfer')
                    ->label('Bukti Transfer')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah ada bukti')
                    ->falseLabel('Belum ada bukti')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('bukti_transfer')->where('bukti_transfer', '!=', ''),
                        false: fn (Builder $query) => $query->where(fn (Builder $q) => $q->whereNull('bukti_transfer')->orWhere('bukti_transfer', '')),
                    ),

                Filter::make('tanggal_between')
                    ->label('Rentang Tanggal')
                    ->form([
                        DatePicker::make('tanggal_from')
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('tanggal_until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['tanggal_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('tanggal', '>=', $date))
                            ->when($data['tanggal_until'] ?? null, fn (Builder $q, $date) => $q->whereDate('tanggal', '<=', $date));
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
