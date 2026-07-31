<?php

namespace App\Filament\Resources\Expos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ExposTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_expo')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Expo')
                    ->description(fn ($record) => $record->periode),

                TextColumn::make('tanggal_mulai')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal')
                    ->description(fn ($record) => 's/d ' . $record->tanggal_selesai->format('d M Y')),

                TextColumn::make('lokasi')
                    ->label('Venue')
                    ->searchable()
                    ->limit(35)
                    ->description(fn ($record) => $record->alamat ? \Str::limit($record->alamat, 40) : null),

                IconColumn::make('status')
                    ->boolean()
                    ->label('Aktif'),
            ])
            ->defaultSort('tanggal_mulai', 'desc')
            ->filters([
                TernaryFilter::make('status')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
