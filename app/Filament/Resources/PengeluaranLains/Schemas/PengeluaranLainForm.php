<?php

namespace App\Filament\Resources\PengeluaranLains\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PengeluaranLainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Detail Pengeluaran')
                    ->description('Informasi utama pengeluaran')
                    ->schema([
                        TextInput::make('nama_pengeluaran')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Pengeluaran')
                            ->placeholder('Contoh: Sewa Panggung')
                            ->columnSpanFull(),

                        DatePicker::make('tanggal')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->label('Tanggal Pengeluaran'),

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->default(Auth::id())
                            ->nullable()
                            ->label('Dibuat Oleh'),

                        Textarea::make('keterangan')
                            ->rows(3)
                            ->nullable()
                            ->label('Keterangan')
                            ->placeholder('Opsional, detail tambahan')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Nominal & Bukti')
                    ->description('Jumlah dan bukti transaksi')
                    ->schema([
                        TextInput::make('nominal')
                            ->integer()
                            ->prefix('Rp')
                            ->required()
                            ->label('Nominal')
                            ->placeholder('0')
                            ->minValue(1)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric(),

                        Select::make('rekening_tujuan_id')
                            ->relationship('rekeningTujuan', 'nama_bank')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Rekening Tujuan')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_bank.' - '.$record->nomor_rekening)
                            ->native(false),

                        FileUpload::make('bukti_transfer')
                            ->directory('bukti-transfer')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->required()
                            ->label('Bukti Transfer')
                            ->helperText('Boleh gambar (JPG/PNG/WebP) atau PDF.')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),

                        FileUpload::make('nota_dinas')
                            ->directory('nota-dinas')
                            ->acceptedFileTypes(['application/pdf'])
                            ->label('Nota Dinas')
                            ->helperText('Upload file PDF Nota Dinas.')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
            ]);
    }
}
