<?php

namespace App\Filament\Resources\PenyelenggaraGalleries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PenyelenggaraGalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('penyelenggara_id')
                    ->relationship('penyelenggara', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Penyelenggara')
                    ->helperText('Pilih penyelenggara terkait untuk galeri ini.'),

                TextInput::make('title')
                    ->label('Judul')
                    ->placeholder('Judul foto atau seri')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->placeholder('Deskripsi singkat tentang foto atau momen yang diabadikan')
                    ->rows(4)
                    ->maxLength(2000)
                    ->columnSpanFull(),

                FileUpload::make('image_path')
                    ->label('Foto')
                    ->image()
                    ->multiple()
                    ->minFiles(1)
                    ->maxFiles(20)
                    ->reorderable()
                    ->downloadable()
                    ->preserveFilenames()
                    ->disk('public')
                    ->directory('penyelenggara-galleries')
                    ->helperText('Unggah beberapa foto. Anda dapat mengubah urutan tampil dengan drag & drop.')
                    ->columnSpanFull(),

                TextInput::make('photographer_name')
                    ->label('Fotografer')
                    ->placeholder('Nama fotografer (opsional)')
                    ->maxLength(255),

                DatePicker::make('photo_date')
                    ->label('Tanggal Foto')
                    ->helperText('Tanggal pengambilan foto (opsional).'),

                TextInput::make('display_order')
                    ->label('Urutan Tampil')
                    ->placeholder('0')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->helperText('Angka lebih kecil akan tampil lebih awal.'),

                Toggle::make('is_featured')
                    ->label('Sorotan')
                    ->helperText('Tandai untuk tampil di bagian sorotan.'),

                Toggle::make('is_published')
                    ->label('Publikasi')
                    ->default(true)
                    ->helperText('Nonaktifkan untuk menyembunyikan dari publik.'),

                TagsInput::make('tags')
                    ->label('Tags')
                    ->placeholder('Tambahkan tag, tekan Enter untuk menambah')
                    ->columnSpanFull(),
            ]);
    }
}
