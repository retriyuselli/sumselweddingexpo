<?php

namespace App\Filament\Resources\PengeluaranLains\Tables;

use App\Models\PengeluaranLain;
use App\Models\RekeningTujuan;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PengeluaranLainsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('tanggal', 'desc')
            ->columns([
                TextColumn::make('nama_pengeluaran')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pengeluaran')
                    ->description(fn (PengeluaranLain $record): ?string => $record->keterangan
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
                    ->label('Rekening Tujuan')
                    ->toggleable()
                    ->description(fn (PengeluaranLain $record): ?string => $record->rekeningTujuan
                        ? $record->rekeningTujuan->nomor_rekening
                        : null),

                TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),

                IconColumn::make('bukti_transfer')
                    ->label('Bukti')
                    ->boolean()
                    ->getStateUsing(fn (PengeluaranLain $record): bool => $record->hasBuktiTransfer())
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-exclamation-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                IconColumn::make('nota_dinas')
                    ->label('Nota Dinas')
                    ->boolean()
                    ->getStateUsing(fn (PengeluaranLain $record): bool => $record->hasNotaDinas())
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-document')
                    ->trueColor('success')
                    ->falseColor('gray'),

                ImageColumn::make('bukti_preview')
                    ->label('Preview')
                    ->getStateUsing(function (PengeluaranLain $record): ?string {
                        $path = $record->bukti_transfer;
                        if (! filled($path)) {
                            return null;
                        }

                        return str_ends_with(strtolower((string) $path), '.pdf') ? null : $path;
                    })
                    ->square()
                    ->size(40)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('rekening_tujuan_id')
                    ->relationship('rekeningTujuan', 'nama_bank')
                    ->getOptionLabelFromRecordUsing(
                        fn (RekeningTujuan $record) => $record->nama_bank.' - '.$record->nomor_rekening
                    )
                    ->searchable()
                    ->preload()
                    ->label('Rekening Tujuan'),
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
                TernaryFilter::make('nota_dinas')
                    ->label('Nota Dinas')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah ada Nota Dinas')
                    ->falseLabel('Belum ada Nota Dinas')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('nota_dinas')->where('nota_dinas', '!=', ''),
                        false: fn (Builder $query) => $query->where(fn (Builder $q) => $q->whereNull('nota_dinas')->orWhere('nota_dinas', '')),
                    ),
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
