<?php

namespace App\Filament\Resources\Expos\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class ExpoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_expo')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Expo'),

                DatePicker::make('tanggal_mulai')
                    ->required()
                    ->label('Tanggal Mulai'),

                DatePicker::make('tanggal_selesai')
                    ->required()
                    ->afterOrEqual('tanggal_mulai')
                    ->label('Tanggal Selesai'),

                TextInput::make('lokasi')
                    ->required()
                    ->maxLength(255),

                Toggle::make('status')
                    ->label('Aktif'),

                Select::make('periode')
                    ->required()
                    ->options([
                        'I' => 'Periode I',
                        'II' => 'Periode II',
                    ])
                    ->placeholder('Pilih periode')
                    ->helperText('Pilih periode event (I atau II)'),
            ]);
    }
}
