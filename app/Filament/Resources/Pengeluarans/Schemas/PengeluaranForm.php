<?php

namespace App\Filament\Resources\Pengeluarans\Schemas;

use Dom\Text;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PengeluaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Detail Pengeluaran')
                    ->description('Informasi utama pengeluaran')
                    ->schema([
                        Select::make('expo_id')
                            ->relationship('expo', 'nama_expo')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Expo')
                            ->native(false)
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_expo.' ('.$record->periode.')')
                            ->columnSpanFull(),

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
                            ->label('Sumber Dana')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_bank.' - '.$record->nomor_rekening)
                            ->native(false),
                        
                        TextInput::make('rek_transfer')
                            ->label('No Rekening Penerima')
                            ->numeric(),

                        TextInput::make('nama_rekening_penerima')
                            ->label('Nama Rekening Penerima'),

                        FileUpload::make('bukti_transfer')
                            ->directory('bukti-transfer')
                            ->image()
                            ->imageEditor()
                            ->label('Bukti Transfer')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
            ]);
    }
}
