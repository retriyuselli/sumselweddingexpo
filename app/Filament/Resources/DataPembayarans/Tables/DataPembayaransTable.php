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
                        'primary' => fn ($state) => $state === 'tf',
                        'warning' => fn ($state) => $state === 'cash',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'tf' ? 'Transfer' : 'Cash'),

                TextColumn::make('rekeningTujuan.nama_bank')
                    ->label('Rekening Tujuan')
                    ->toggleable(),

                ImageColumn::make('bukti_transfer')
                    ->label('Bukti')
                    ->square()
                    ->size(40)
                    ->toggleable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('metode_pembayaran')
                    ->options([
                        'tf' => 'Transfer',
                        'cash' => 'Cash',
                    ])
                    ->label('Metode'),
                SelectFilter::make('rekening_tujuan_id')
                    ->relationship('rekeningTujuan', 'nama_bank')
                    ->label('Rekening Tujuan'),
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
