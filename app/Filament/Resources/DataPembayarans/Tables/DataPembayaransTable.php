<?php

namespace App\Filament\Resources\DataPembayarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
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
                TextColumn::make('nama_pembayar')
                    ->label('Penyetor')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('partisipasi.vendor.nama_vendor')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nominal')
                    ->sortable()
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('tanggal_bayar')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal Bayar'),

                BadgeColumn::make('metode_pembayaran')
                    ->label('Metode')
                    ->colors([
                        'primary' => fn ($state) => $state === 'Transfer Bank',
                        'warning' => fn ($state) => $state === 'Tunai',
                        'success' => fn ($state) => $state === 'QRIS',
                    ])
                    ->formatStateUsing(fn ($state) => $state ?? '-'),

                TextColumn::make('rekeningTujuan.nama_bank')
                    ->label('Bank Tujuan')
                    ->formatStateUsing(fn ($state, $record) => $record?->rekeningTujuan?->nama_bank.' - '.$record?->rekeningTujuan?->nomor_rekening)
                    ->toggleable(),

                ImageColumn::make('bukti_transfer')
                    ->label('Bukti')
                    ->square()
                    ->size(40)
                    ->toggleable(),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('metode_pembayaran')
                    ->options([
                        'Transfer Bank' => 'Transfer Bank',
                        'Tunai' => 'Tunai',
                        'QRIS' => 'QRIS',
                    ])
                    ->label('Metode'),
                SelectFilter::make('rekening_tujuan_id')
                    ->relationship('rekeningTujuan', 'nama_bank')
                    ->label('Rekening Tujuan'),
                SelectFilter::make('partisipasi_id')
                    ->relationship('partisipasi.vendor', 'nama_vendor')
                    ->label('Vendor'),
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
