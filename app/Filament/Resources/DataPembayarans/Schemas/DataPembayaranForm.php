<?php

namespace App\Filament\Resources\DataPembayarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DataPembayaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('partisipasi_id')
                    ->relationship('partisipasi', 'id')
                    ->searchable()
                    ->required()
                    ->label('Partisipasi')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 'Partisipasi #'.$record->id.' - '.optional($record->vendor)->nama_vendor),

                TextInput::make('nama_pembayar')
                    ->required()
                    ->maxLength(255),

                TextInput::make('nominal')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                DatePicker::make('tanggal_bayar')
                    ->required()
                    ->label('Tanggal Bayar'),

                Select::make('metode_pembayaran')
                    ->options([
                        'Transfer Bank' => 'Transfer Bank',
                        'Tunai' => 'Tunai',
                        'QRIS' => 'QRIS',
                    ])
                    ->default('Transfer Bank')
                    ->required()
                    ->label('Metode Pembayaran'),

                FileUpload::make('bukti_transfer')
                    ->directory('bukti-transfer')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->label('Bukti Transfer'),

                Select::make('rekening_tujuan_id')
                    ->relationship('rekeningTujuan', 'nama_bank')
                    ->searchable()
                    ->required()
                    ->label('Rekening Tujuan')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_bank.' - '.$record->nomor_rekening),

                Textarea::make('keterangan')
                    ->rows(3)
                    ->nullable(),
            ]);
    }
}
