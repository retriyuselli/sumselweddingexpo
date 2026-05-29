<?php

namespace App\Filament\Resources\Partisipasis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PartisipasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->sortable()
                    ->description(fn ($record) => $record->expo?->periode),

                TextColumn::make('vendor.nama_vendor')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable()
                    ->description(fn ($record) => $record->categoryTenant?->category?->label()),

                TextColumn::make('tanggal_booking')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal Booking'),

                TextColumn::make('harga_jual')
                    ->sortable()
                    ->label('Harga Jual')
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                IconColumn::make('is_barter')
                    ->boolean()
                    ->label('Barter')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('barter_nominal')
                    ->sortable()
                    ->label('Nominal Barter')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                BadgeColumn::make('status_pembayaran')
                    ->label('Status Pembayaran'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('expo_id')
                    ->relationship('expo', 'nama_expo')
                    ->label('Expo'),
                SelectFilter::make('vendor_id')
                    ->relationship('vendor', 'nama_vendor')
                    ->label('Vendor'),
                SelectFilter::make('status_pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum_lunas' => 'Belum Lunas',
                        'dp' => 'DP (Down Payment)',
                        'cicilan' => 'Cicilan',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
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
