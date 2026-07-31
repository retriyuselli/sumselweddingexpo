<?php

namespace App\Filament\Resources\ProductVendors\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductVendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Produk Vendor')
                    ->description('Informasi utama produk')
                    ->schema([
                        Select::make('vendor_id')
                            ->relationship('vendor', 'nama_vendor')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Vendor')
                            ->columnSpan(1),

                        TextInput::make('nama_produk')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Produk')
                            ->placeholder('Contoh: Paket Wedding Organizer Platinum')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(1),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Slug URL')
                            ->placeholder('paket-wedding-organizer-platinum')
                            ->columnSpanFull(),

                        RichEditor::make('deskripsi')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Harga & Stok')
                    ->description('Pengaturan harga, stok, dan status')
                    ->schema([
                        TextInput::make('harga')
                            ->numeric()
                            ->minValue(0)
                            ->label('Harga (Rp)')
                            ->placeholder('Contoh: 25000000'),

                        TextInput::make('dp_fixed')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->label('DP Nominal Tetap (Rp)')
                            ->helperText('Wajib. Nominal DP yang dibayarkan saat checkout'),

                        TextInput::make('stok')
                            ->numeric()
                            ->minValue(0)
                            ->label('Stok'),

                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Media')
                    ->description('Upload gambar produk')
                    ->schema([
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
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
