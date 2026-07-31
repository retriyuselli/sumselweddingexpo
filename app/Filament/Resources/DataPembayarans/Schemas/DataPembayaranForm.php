<?php

namespace App\Filament\Resources\DataPembayarans\Schemas;

use App\Models\Partisipasi;
use App\Models\RekeningTujuan;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class DataPembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pembayaran')
                    ->columns(2)
                    ->schema([
                        Select::make('partisipasi_id')
                            ->relationship('partisipasi', 'id')
                            ->getOptionLabelFromRecordUsing(fn (Partisipasi $record) => "{$record->vendor->nama_vendor} - {$record->expo->nama_expo}")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Vendor & Expo')
                            ->columnSpanFull(),

                        TextInput::make('nama_pembayar')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Penyetor'),

                        DatePicker::make('tanggal_bayar')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->label('Tanggal Bayar'),

                        TextInput::make('nominal')
                            ->required()
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->label('Nominal Pembayaran'),

                        Select::make('metode_pembayaran')
                            ->options([
                                'Transfer Bank' => 'Transfer Bank',
                                'Cash' => 'Cash',
                                'QRIS' => 'QRIS',
                                'Cek' => 'Cek',
                                'Giro' => 'Giro',
                            ])
                            ->required()
                            ->default('Transfer Bank')
                            ->label('Metode Pembayaran'),

                        Select::make('rekening_tujuan_id')
                            ->relationship('rekeningTujuan', 'nama_bank')
                            ->getOptionLabelFromRecordUsing(fn (RekeningTujuan $record) => "{$record->nama_bank} - {$record->nomor_rekening} ({$record->nama_pemilik})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Rekening Tujuan'),

                        Select::make('termin_pembayaran')
                            ->options([
                                'Termin 1' => 'Termin 1',
                                'Termin 2' => 'Termin 2',
                                'Termin 3' => 'Termin 3',
                                'Pelunasan' => 'Pelunasan',
                            ])
                            ->required()
                            ->label('Tahap Pembayaran'),

                        Textarea::make('keterangan')
                            ->columnSpanFull()
                            ->label('Keterangan Tambahan'),

                        FileUpload::make('bukti_transfer')
                            ->directory('bukti-transfer')
                            ->image()
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull()
                            ->label('Bukti Transfer'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
