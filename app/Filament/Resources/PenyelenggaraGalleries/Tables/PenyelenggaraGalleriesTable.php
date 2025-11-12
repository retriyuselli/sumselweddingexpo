<?php

namespace App\Filament\Resources\PenyelenggaraGalleries\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PenyelenggaraGalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('primary_image_url')
                    ->label('Foto')
                    ->square()
                    ->extraImgAttributes(['loading' => 'lazy']),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('penyelenggara.name')
                    ->label('Penyelenggara')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_featured')
                    ->label('Sorotan')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Publikasi')
                    ->boolean(),
                TextColumn::make('photo_date')
                    ->label('Tanggal')
                    ->date(),
                TextColumn::make('display_order')
                    ->label('Urutan')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('penyelenggara')
                    ->label('Penyelenggara')
                    ->relationship('penyelenggara', 'name'),
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
