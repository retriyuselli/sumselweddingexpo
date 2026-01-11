<?php

namespace App\Filament\Resources\Penyelenggaras\Schemas;

use App\Models\Penyelenggara;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PenyelenggaraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->placeholder('Nama penyelenggara')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('penyelenggaras')
                    ->disk('public')
                    ->maxSize(5120),

                FileUpload::make('favicon')
                    ->label('Favicon')
                    ->image()
                    ->directory('penyelenggaras/favicons')
                    ->disk('public')
                    ->maxSize(1024),

                FileUpload::make('banner')
                    ->label('Banner Home')
                    ->image()
                    ->directory('penyelenggaras/banners')
                    ->disk('public')
                    ->maxSize(5120)
                    ->helperText('Rekomendasi ukuran: 1920x1080px (Landscape Full Width).')
                    ->columnSpanFull(),

                FileUpload::make('banner_2')
                    ->label('Banner Samping (Desktop)')
                    ->image()
                    ->directory('penyelenggaras/banners')
                    ->disk('public')
                    ->maxSize(5120)
                    ->helperText('Rekomendasi ukuran: 800x600px atau rasio 4:3.')
                    ->columnSpanFull(),

                TextInput::make('jam_operasional')
                    ->label('Jam Operasional')
                    ->placeholder('Contoh: Senin–Jumat 09:00–17:00')
                    ->maxLength(255),

                Textarea::make('alamat')
                    ->label('Alamat')
                    ->placeholder('Alamat lengkap penyelenggara')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('email')
                    ->email()
                    ->label('Email')
                    ->placeholder('nama@contoh.com')
                    ->maxLength(255)
                    ->unique(table: Penyelenggara::class, column: 'email', ignoreRecord: true)
                    ->helperText('Biarkan kosong jika tidak ada email resmi.'),

                TextInput::make('no_tlp')
                    ->label('No. Telepon')
                    ->placeholder('Contoh: +62 812-3456-7890')
                    ->tel()
                    ->maxLength(255)
                    ->rule('regex:/^[0-9+\s\-()]{6,}$/'),

                TextInput::make('instagram')
                    ->label('Instagram')
                    ->placeholder('Username tanpa @, misal: sumselweddingexpo')
                    ->maxLength(255)
                    ->rule('regex:/^[A-Za-z0-9._]{1,30}$/')
                    ->helperText('Masukkan username, bukan URL.'),

                TextInput::make('tiktok')
                    ->label('TikTok')
                    ->placeholder('Username tanpa @, misal: sumselweddingexpo')
                    ->maxLength(255)
                    ->rule('regex:/^[A-Za-z0-9._]{1,30}$/')
                    ->helperText('Masukkan username, bukan URL.'),
            ]);
    }
}
