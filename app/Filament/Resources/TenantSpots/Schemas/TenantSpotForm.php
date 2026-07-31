<?php

namespace App\Filament\Resources\TenantSpots\Schemas;

use App\Models\Expo;
use App\Models\TenantSpot;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenantSpotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Booth')
                    ->columns(2)
                    ->schema([
                        Select::make('expo_id')
                            ->relationship('expo', 'nama_expo')
                            ->getOptionLabelFromRecordUsing(fn (Expo $record) => $record->nama_expo . ' (' . $record->periode . ')')
                            ->searchable()
                            ->required()
                            ->live()
                            ->label('Expo')
                            ->columnSpanFull(),

                        TextInput::make('blok')
                            ->required()
                            ->maxLength(5)
                            ->placeholder('A')
                            ->label('Blok')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('kode_booth', TenantSpot::buildKode(
                                    $state ?? '',
                                    (int) ($get('nomor') ?: 0),
                                ))
                            ),

                        TextInput::make('nomor')
                            ->required()
                            ->integer()
                            ->minValue(1)
                            ->placeholder('1')
                            ->label('Nomor Booth')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('kode_booth', TenantSpot::buildKode(
                                    $get('blok') ?? '',
                                    (int) ($state ?: 0),
                                ))
                            ),

                        TextInput::make('kode_booth')
                            ->required()
                            ->maxLength(20)
                            ->label('Kode Booth')
                            ->helperText('Otomatis terisi dari Blok + Nomor, atau isi manual.')
                            ->columnSpanFull(),

                        Select::make('section')
                            ->options([
                                'kiri'  => 'Kiri',
                                'kanan' => 'Kanan',
                            ])
                            ->nullable()
                            ->placeholder('— Tidak ada —')
                            ->label('Seksi')
                            ->helperText('Isi jika blok dibagi menjadi beberapa seksi (misal Blok B kiri/kanan).')
                            ->columnSpanFull(),
                    ]),

                Section::make('Posisi di Layout')
                    ->columns(2)
                    ->description('Tentukan posisi baris dan kolom booth di dalam blok/seksinya.')
                    ->schema([
                        TextInput::make('baris')
                            ->required()
                            ->integer()
                            ->minValue(1)
                            ->label('Baris')
                            ->helperText('Baris dari atas (1 = paling atas).'),

                        TextInput::make('kolom')
                            ->required()
                            ->integer()
                            ->minValue(1)
                            ->label('Kolom')
                            ->helperText('Kolom dari kiri (1 = paling kiri).'),
                    ]),
            ]);
    }
}
