<?php

namespace App\Filament\Resources\PengeluaranLains\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PengeluaranLainForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_pengeluaran')
                    ->required()
                    ->maxLength(255),

                Textarea::make('keterangan')
                    ->rows(3)
                    ->nullable(),

                TextInput::make('nominal')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal'),

                Select::make('rekening_tujuan_id')
                    ->relationship('rekeningTujuan', 'nama_bank')
                    ->searchable()
                    ->required()
                    ->label('Rekening Tujuan')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nama_bank.' - '.$record->nomor_rekening),

                FileUpload::make('bukti_transfer')
                    ->directory('bukti-transfer')
                    ->image()
                    ->imageEditor()
                    ->required()
                    ->label('Bukti Transfer'),

                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->default(auth()->id())
                    ->nullable()
                    ->label('Dibuat Oleh'),
            ]);
    }
}
