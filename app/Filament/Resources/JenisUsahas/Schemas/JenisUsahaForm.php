<?php

namespace App\Filament\Resources\JenisUsahas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JenisUsahaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_jenis_usaha')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Nama Jenis Usaha'),
            ]);
    }
}
