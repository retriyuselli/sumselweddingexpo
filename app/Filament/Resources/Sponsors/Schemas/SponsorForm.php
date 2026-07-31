<?php

namespace App\Filament\Resources\Sponsors\Schemas;

use App\Enums\SponsorType;
use App\Models\Expo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

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
                            ->getOptionLabelFromRecordUsing(fn (Expo $record) => self::expoLabel($record))
                            ->label('Nama Expo')
                            ->helperText('Ditampilkan: nama expo · periode · tanggal · lokasi')
                            ->searchable()
                            ->preload()
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

                        Textarea::make('kewajiban')
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

    protected static function expoLabel(Expo $expo): string
    {
        $tanggal = null;
        if ($expo->tanggal_mulai) {
            $tanggal = $expo->tanggal_mulai->format('d M Y');
            if ($expo->tanggal_selesai && ! $expo->tanggal_mulai->equalTo($expo->tanggal_selesai)) {
                $tanggal .= ' – '.$expo->tanggal_selesai->format('d M Y');
            }
        }

        $parts = array_filter([
            $expo->periode ? 'Periode '.$expo->periode : null,
            $tanggal,
            $expo->lokasi ? Str::limit($expo->lokasi, 40) : null,
            $expo->status ? 'Aktif' : 'Nonaktif',
        ]);

        return $parts === []
            ? $expo->nama_expo.' [#'.$expo->id.']'
            : $expo->nama_expo.' ('.implode(' · ', $parts).')';
    }
}
