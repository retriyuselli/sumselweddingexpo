<?php

namespace App\Filament\Resources\Penyelenggaras\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class GalleriesRelationManager extends RelationManager
{
    protected static string $relationship = 'galleries';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->maxLength(255)
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Foto')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->downloadable()
                    ->disk('public')
                    ->directory('penyelenggara-galleries')
                    ->columnSpanFull(),
                TextInput::make('photographer_name')
                    ->label('Fotografer')
                    ->maxLength(255),
                DatePicker::make('photo_date')
                    ->label('Tanggal Foto'),
                TextInput::make('display_order')
                    ->label('Urutan Tampil')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_featured')
                    ->label('Sorotan'),
                Toggle::make('is_published')
                    ->label('Publikasi')
                    ->default(true),
                TagsInput::make('tags')
                    ->label('Tags')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
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
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
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