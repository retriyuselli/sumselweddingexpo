<?php

namespace App\Filament\Resources\Vendors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class VendorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('nama_vendor')
                ->searchable()
                ->sortable()
                ->label('Nama Vendor'),
                
            TextColumn::make('user.name')
                ->label('Customer')
                ->badge()
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => $state ?? '—'),

            TextColumn::make('jenisUsaha.nama_jenis_usaha')
                ->sortable()
                ->label('Jenis Usaha'),

            TextColumn::make('kota')
                ->searchable()
                ->sortable(),

            TextColumn::make('no_telepon')
                ->label('Telepon')
                ->toggleable(),

            TextColumn::make('email')
                ->searchable(),

            TextColumn::make('nama_pic')
                ->label('PIC')
                ->toggleable(),

            TextColumn::make('no_wa_pic')
                ->label('WA PIC')
                ->toggleable(),

            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('jenis_usaha_id')
                    ->relationship('jenisUsaha', 'nama_jenis_usaha')
                    ->label('Jenis Usaha'),
                \Filament\Tables\Filters\TernaryFilter::make('has_user')
                    ->label('Terhubung ke Customer')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya yang terhubung')
                    ->falseLabel('Tanpa customer')
                    ->queries(
                        true: fn ($query) => $query->whereHas('user'),
                        false: fn ($query) => $query->whereDoesntHave('user'),
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
