<?php

namespace App\Filament\Resources\Vendors\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'nama_produk';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_produk')
                    ->label('Nama Produk')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug URL')
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->required(),
                RichEditor::make('deskripsi')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                TextInput::make('harga')
                    ->label('Harga (Rp)')
                    ->numeric()
                    ->minValue(0),
                TextInput::make('stok')
                    ->label('Stok')
                    ->numeric()
                    ->minValue(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
                \Filament\Forms\Components\FileUpload::make('foto_url')
                    ->label('Foto Produk')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg','image/png'])
                    ->disk('public')
                    ->directory('product-vendors')
                    ->maxSize(2048)
                    ->downloadable()
                    ->helperText('Format JPG/PNG, maks 2MB, disimpan di storage publik')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_produk')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float)($state ?? 0), 0, ',', '.')),
                TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Aktif'),
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
