<?php

namespace App\Filament\Resources\Expos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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

                Section::make('Hadiah Pengunjung')
                    ->description('Informasi hadiah yang ditampilkan di halaman absensi expo ini. Tidak mengubah aturan pencatatan kehadiran atau menentukan pemenang.')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('visitor_prizes_enabled')
                            ->label('Tampilkan hadiah di halaman absensi')
                            ->default(false)
                            ->helperText('Aktifkan setelah rincian hadiah siap diumumkan.'),
                        Repeater::make('visitor_prizes')
                            ->label('Daftar Hadiah')
                            ->schema([
                                TextInput::make('name')->label('Nama Hadiah')->required()->maxLength(255)
                                    ->placeholder('Contoh: Voucher belanja'),
                                TextInput::make('quantity')->label('Jumlah Hadiah')->numeric()->integer()->minValue(1)->maxValue(100000)->default(1)->required(),
                                Textarea::make('description')->label('Keterangan Hadiah')->rows(2)->maxLength(500)->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->maxItems(30)
                            ->addActionLabel('Tambah Hadiah')
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->reorderable()
                            ->columnSpanFull(),
                        Textarea::make('visitor_prize_terms')
                            ->label('Ketentuan dan Pengumuman Hadiah')
                            ->placeholder('Isi syarat peserta, jadwal pengundian, dan cara pengambilan hadiah sesuai ketentuan panitia.')
                            ->rows(4)
                            ->maxLength(2000)
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
