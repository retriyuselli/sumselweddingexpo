<?php

namespace App\Filament\Resources\Pengeluarans\Schemas;

use App\Models\Expo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Builder;
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
                            ->relationship(
                                name: 'expo',
                                titleAttribute: 'nama_expo',
                                modifyQueryUsing: fn (Builder $query) => $query->orderByDesc('tanggal_mulai'),
                            )
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Expo')
                            ->native(false)
                            ->getOptionLabelFromRecordUsing(fn (Expo $record) => $record->labelForSelect())
                            ->helperText('Label menampilkan nama · periode · tanggal pelaksanaan.')
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
                            ->default(now())
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
                            ->label('Sumber Dana')
                            ->getOptionLabelFromRecordUsing(
                                fn ($record) => $record->nama_bank.' - '.$record->nomor_rekening.' ('.$record->nama_pemilik.')'
                            )
                            ->native(false),

                        TextInput::make('rek_transfer')
                            ->label('No Rekening Penerima')
                            ->tel()
                            ->maxLength(50)
                            ->placeholder('Contoh: 1234567890'),

                        TextInput::make('nama_rekening_penerima')
                            ->label('Nama Rekening Penerima')
                            ->maxLength(255)
                            ->placeholder('Nama pemilik rekening penerima'),

                        FileUpload::make('bukti_transfer')
                            ->directory('bukti-transfer')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'image/jpeg',
                                'image/png',
                                'image/webp',
                            ])
                            ->label('Bukti Transfer')
                            ->helperText('Boleh gambar (JPG/PNG/WebP) atau PDF.')
                            ->openable()
                            ->downloadable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),
            ]);
    }
}
