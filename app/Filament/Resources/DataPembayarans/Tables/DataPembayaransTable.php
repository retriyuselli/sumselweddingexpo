<?php

namespace App\Filament\Resources\DataPembayarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class DataPembayaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('partisipasi.vendor.nama_vendor')
                    ->label('Vendor')
                    ->description(fn ($record) => $record->partisipasi->expo->nama_expo ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nama_pembayar')
                    ->label('Penyetor')
                    ->searchable(),
                TextColumn::make('nominal')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('tanggal_bayar')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('metode_pembayaran')
                    ->badge()
                    ->colors([
                        'success' => 'Transfer Bank',
                        'warning' => 'Cash',
                        'info' => 'QRIS',
                    ])
                    ->searchable(),
                TextColumn::make('termin_pembayaran')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('rekeningTujuan.nama_bank')
                    ->label('Bank Tujuan')
                    ->description(fn ($record) => $record->rekeningTujuan->nomor_rekening ?? '-')
                    ->sortable(),
                ImageColumn::make('bukti_transfer')
                    ->label('Bukti'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('metode_pembayaran')
                    ->options([
                        'Transfer Bank' => 'Transfer Bank',
                        'Cash' => 'Cash',
                        'QRIS' => 'QRIS',
                        'Cek' => 'Cek',
                        'Giro' => 'Giro',
                    ]),
                SelectFilter::make('termin_pembayaran')
                    ->options([
                        'Termin 1' => 'Termin 1',
                        'Termin 2' => 'Termin 2',
                        'Termin 3' => 'Termin 3',
                        'Pelunasan' => 'Pelunasan',
                    ]),
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
