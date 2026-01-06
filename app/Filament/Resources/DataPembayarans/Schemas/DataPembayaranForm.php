<?php

namespace App\Filament\Resources\DataPembayarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DataPembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Informasi Pembayaran')
                    ->description('Detail identitas pembayar dan transaksi')
                    ->schema([
                        Select::make('partisipasi_id')
                            ->relationship('partisipasi', 'id')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Vendor / Partisipasi')
                            ->getOptionLabelFromRecordUsing(fn ($record) => optional($record->vendor)->nama_vendor . ' (Partisipasi #' . $record->id . ')')
                            ->columnSpanFull(),

                        TextInput::make('nama_pembayar')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Penyetor')
                            ->placeholder('Contoh: Budi Santoso')
                            ->prefixIcon('heroicon-m-user'),

                        DatePicker::make('tanggal_bayar')
                            ->required()
                            ->native(false)
                            ->displayFormat('d F Y')
                            ->label('Tanggal Pembayaran')
                            ->default(now()),

                        Select::make('metode_pembayaran')
                            ->options([
                                'Transfer Bank' => 'Transfer Bank',
                                'Tunai' => 'Tunai',
                                'QRIS' => 'QRIS',
                            ])
                            ->default('Transfer Bank')
                            ->required()
                            ->label('Metode Pembayaran')
                            ->native(false),
                            
                        Select::make('rekening_tujuan_id')
                            ->relationship('rekeningTujuan', 'nama_bank')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Bank Tujuan')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_bank.' - '.$record->nomor_rekening)
                            ->native(false),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Nominal & Bukti')
                    ->description('Jumlah pembayaran dan bukti transfer')
                    ->schema([
                        TextInput::make('nominal')
                            ->numeric()
                            ->prefix('Rp')
                            ->required()
                            ->label('Nominal Pembayaran')
                            ->placeholder('0')
                            ->minValue(1),

                        FileUpload::make('bukti_transfer')
                            ->directory('bukti-transfer')
                            ->image()
                            ->imageEditor()
                            ->required()
                            ->label('Bukti Transfer')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                            
                        Textarea::make('keterangan')
                            ->rows(3)
                            ->nullable()
                            ->label('Catatan Tambahan')
                            ->placeholder('Opsional, jika ada catatan khusus')
                            ->columnSpanFull(),
                    ])
                    ->columns(1)
                    ->columnSpan(1),
            ]);
    }
}
