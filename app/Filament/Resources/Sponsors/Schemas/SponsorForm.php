<?php

namespace App\Filament\Resources\Sponsors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SponsorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Sponsor')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Sponsor')
                            ->required()
                            ->columnSpan(1),
                        
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('vendors')
                            ->columnSpan(1),
                        
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->columnSpan(1),
                        
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->columnSpan(1),
                        
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpan(2),
                    ]),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
