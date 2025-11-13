<?php

namespace App\Filament\Resources\RekeningTujuans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RekeningTujuanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_bank')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Bank'),

                TextInput::make('nomor_rekening')
                    ->required()
                    ->maxLength(255)
                    ->label('Nomor Rekening'),

                TextInput::make('nama_pemilik')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Pemilik'),
            ]);
    }
}
