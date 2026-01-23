<?php

namespace App\Filament\Resources\Sponsors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Support\RawJs;
use Filament\Schemas\Schema;
use App\Enums\SponsorType;

class SponsorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Sponsor')
                    ->columns(2)
                    ->schema([
                        Select::make('expo_id')
                            ->relationship('expo', 'nama_expo')
                            ->label('Nama Expo')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('Nama Sponsor')
                            ->required()
                            ->columnSpan(1),

                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->directory('sponsors')
                            ->disk('public')
                            ->maxSize(5120)
                            ->columnSpan(1),

                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->columnSpan(1),

                        Select::make('jenis_sponsor')
                            ->label('Jenis Sponsor')
                            ->options(SponsorType::options())
                            ->native(false)
                            ->required()
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

                Section::make('Detail Kesepakatan')
                    ->schema([
                        TagsInput::make('bantuan')
                            ->label('Bentuk Bantuan')
                            ->suggestions([
                                'Uang Tunai',
                                'Promosi',
                                'Barang',
                                'Jasa',
                                'Venue',
                                'Konsumsi',
                            ])
                            ->placeholder('Tambah bentuk bantuan')
                            ->live()
                            ->columnSpanFull(),
                        
                        TextInput::make('nominal')
                            ->label('Nominal Uang Tunai')
                            ->prefix('Rp')
                            ->numeric()
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->visible(fn ($get) => in_array('Uang Tunai', $get('bantuan') ?? []))
                            ->columnSpanFull(),

                        RichEditor::make('kewajiban')
                            ->label('Kewajiban & Kesepakatan')
                            ->columnSpanFull(),
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
