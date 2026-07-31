<?php

namespace App\Filament\Resources\Expos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExpoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Expo')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nama_expo')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Expo')
                            ->columnSpanFull(),

                        DatePicker::make('tanggal_mulai')
                            ->required()
                            ->label('Tanggal Mulai'),

                        DatePicker::make('tanggal_selesai')
                            ->required()
                            ->afterOrEqual('tanggal_mulai')
                            ->label('Tanggal Selesai'),

                        TextInput::make('lokasi')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Venue')
                            ->columnSpanFull(),

                        Textarea::make('alamat')
                            ->required()
                            ->rows(3)
                            ->label('Alamat Lengkap')
                            ->columnSpanFull(),
                    ]),

                Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
                        TextInput::make('periode')
                            ->required()
                            ->maxLength(50)
                            ->label('Periode')
                            ->placeholder('Contoh: I, II, 2026, Jan-2026')
                            ->helperText('Isi bebas sesuai penamaan periode event.'),

                        Toggle::make('status')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false),
                    ]),
            ]);
    }
}
