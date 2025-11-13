<?php

namespace App\Filament\Resources\Penyelenggaras\Schemas;

use App\Models\Penyelenggara;
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
