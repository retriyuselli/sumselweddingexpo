<?php

namespace App\Filament\Resources\Doorprizes\Tables;

use App\Models\Doorprize;
use App\Models\Expo;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DoorprizesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pemenang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Doorprize $record): ?string => $record->nik),
                ImageColumn::make('foto_ktp')
                    ->label('Foto KTP')
                    ->disk('local')
                    ->visibility('private')
                    ->circular()
                    ->checkFileExistence(false),
                TextColumn::make('partisipasi.expo.nama_expo')
                    ->label('Expo')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Doorprize $record): ?string => $record->partisipasi?->expo?->labelDetails()),
                TextColumn::make('partisipasi.vendor.nama_vendor')
                    ->label('Tenant / Vendor')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->placeholder('—'),
                TextColumn::make('kodevoucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('success')
                    ->fontFamily('mono'),
                TextColumn::make('total_nom_trx')
                    ->label('Total Transaksi')
                    ->state(fn (Doorprize $record): string => Doorprize::formatRupiah($record->total_nominal_transaksi))
                    ->badge()
                    ->color('warning'),
                TextColumn::make('total_no_rev')
                    ->label('Total Revenue')
                    ->state(fn (Doorprize $record): string => Doorprize::formatRupiah($record->total_nominal_revenue))
                    ->badge()
                    ->color('success'),
                TextColumn::make('no_wa')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sudah_download_tring')
                    ->label('TRING')
                    ->badge()
                    ->formatStateUsing(fn (?bool $state): string => $state ? 'Sudah' : 'Belum')
                    ->color(fn (?bool $state): string => $state ? 'success' : 'warning')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('expo')
                    ->label('Filter Expo')
                    ->options(fn (): array => Expo::query()
                        ->orderByDesc('tanggal_mulai')
                        ->orderByDesc('id')
                        ->get()
                        ->mapWithKeys(fn (Expo $expo) => [$expo->id => $expo->labelForSelect()])
                        ->all())
                    ->query(function (Builder $query, array $data): void {
                        if (! empty($data['value'])) {
                            $query->whereHas('partisipasi', function (Builder $query) use ($data): void {
                                $query->where('expo_id', $data['value']);
                            });
                        }
                    })
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sudah_download_tring')
                    ->label('Status TRING')
                    ->options([
                        '1' => 'Sudah download',
                        '0' => 'Belum download',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('downloadForm')
                        ->label('Lihat / Cetak Form TRING')
                        ->icon('heroicon-m-arrow-top-right-on-square')
                        ->color('success')
                        ->url(fn (Doorprize $record) => route('doorprizes.form-tring-pegadaian.pdf', $record))
                        ->openUrlInNewTab(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada data doorprize')
            ->emptyStateDescription('Tambahkan pemenang undian untuk mulai mencatat data.');
    }
}
