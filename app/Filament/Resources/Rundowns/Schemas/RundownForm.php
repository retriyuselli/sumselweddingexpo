<?php

namespace App\Filament\Resources\Rundowns\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RundownForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Rundown')
                    ->description('Masukkan detail jadwal acara disini.')
                    ->schema([
                        Select::make('expo_id')
                            ->relationship('expo', 'nama_expo')
                            ->searchable()
                            ->preload()
                            ->label('Nama Expo')
                            ->required()
                            ->columnSpanFull(),
                        
                        DatePicker::make('tanggal')
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->label('Tanggal Acara')
                            ->required(),
                            
                        TextInput::make('waktu')
                            ->placeholder('Contoh: 09:00 - 10:00')
                            ->label('Waktu Pelaksanaan')
                            ->required(),
                            
                        TextInput::make('acara')
                            ->placeholder('Contoh: Opening Ceremony')
                            ->label('Nama Acara')
                            ->required()
                            ->columnSpanFull(),
                            
                        TextInput::make('lokasi')
                            ->placeholder('Contoh: Main Stage')
                            ->label('Lokasi (Opsional)')
                            ->columnSpanFull(),

                        Textarea::make('deskripsi')
                            ->label('Deskripsi (Opsional)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
