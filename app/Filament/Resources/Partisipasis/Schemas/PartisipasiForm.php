<?php

namespace App\Filament\Resources\Partisipasis\Schemas;

use App\Models\CategoryTenant;
use App\Models\Expo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class PartisipasiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Informasi Expo & Vendor')
                    ->schema([
                        Select::make('expo_id')
                            ->relationship('expo', 'nama_expo')
                            ->getOptionLabelFromRecordUsing(fn (Expo $record) => $record->nama_expo.' ('.$record->periode.')')
                            ->required()
                            ->live()
                            ->label('Expo')
                            ->columnSpan(1)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('category_tenant_id', null);
                            }),

                        DatePicker::make('tanggal_booking')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->default(now())
                            ->label('Tanggal Booking')
                            ->columnSpan(1),

                        Select::make('vendor_id')
                            ->relationship('vendor', 'nama_vendor')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->label('Vendor Utama')
                            ->columnSpan(1),

                        Select::make('vendor_pendamping')
                            ->options(function () {
                                return \App\Models\Vendor::all()->pluck('nama_vendor', 'id');
                            })
                            ->searchable()
                            ->multiple()
                            ->preload()
                            ->label('Vendor Pendamping')
                            ->placeholder('Opsional')
                            ->nullable()
                            ->columnSpan(1),    
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Kategori & Lokasi Tenant')
                    ->schema([
                        Select::make('category_tenant_id')
                            ->label('Kategori Tenant')
                            ->options(function (callable $get) {
                                $expoId = $get('expo_id');
                                if (! $expoId) {
                                    return [];
                                }

                                return CategoryTenant::where('expo_id', $expoId)
                                    ->where('status', true)
                                    ->get()
                                    ->mapWithKeys(fn ($item) => [$item->id => $item->category->label()]);
                            })
                            ->required()
                            ->live()
                            ->label('Kategori Tenant')
                            ->helperText('Pilih expo terlebih dahulu')
                            ->columnSpan(1)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                if ($state) {
                                    $categoryTenant = CategoryTenant::find($state);
                                    if ($categoryTenant) {
                                        $hargaJual = $categoryTenant->harga_jual;
                                        $set('harga_jual', $hargaJual);

                                        $diskon = (int) str_replace(',', '', $get('diskon') ?? 0);
                                        $set('harga_bersih', max(0, $hargaJual - $diskon));
                                    }
                                }
                            }),

                        TextInput::make('blok_tenant')
                            ->maxLength(255)
                            ->label('Blok/Nomor Tenant')
                            ->placeholder('Contoh: A-01, B-12')
                            ->nullable()
                            ->columnSpan(1),

                        TextInput::make('harga_jual')
                            ->prefix('Rp')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->rule('numeric')
                            ->label('Harga Jual')
                            ->helperText('Harga akan otomatis terisi sesuai kategori tenant')
                            ->columnSpan(1),

                        TextInput::make('diskon')
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->rule('numeric')
                            ->label('Diskon / Potongan Harga')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $hargaJual = (int) str_replace(',', '', $get('harga_jual') ?? 0);
                                $diskon = (int) str_replace(',', '', $state ?? 0);
                                $set('harga_bersih', max(0, $hargaJual - $diskon));
                            })
                            ->columnSpan(1),

                        TextInput::make('harga_bersih')
                            ->integer()
                            ->prefix('Rp')
                            ->default(0)
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->readOnly()
                            ->label('Harga Bersih')
                            ->helperText('Otomatis: Harga Jual - Diskon')
                            ->dehydrated()
                            ->columnSpan(1),

                        Textarea::make('keterangan')
                            ->label('Keterangan1')
                            ->nullable(),

                        Toggle::make('is_barter')
                            ->label('Apakah ada barter?')
                            ->live()
                            ->columnSpanFull(),

                        Textarea::make('barter_description')
                            ->label('Keterangan Tambahan Barter')
                            ->visible(fn (Get $get) => $get('is_barter'))
                            ->required(fn (Get $get) => $get('is_barter'))
                            ->columnSpanFull(),

                        TextInput::make('barter_nominal')
                            ->label('Nominal Barter')
                            ->numeric()
                            ->prefix('Rp')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->visible(fn (Get $get) => $get('is_barter'))
                            ->required(fn (Get $get) => $get('is_barter'))
                            ->helperText('Tidak termasuk dalam total pembayaran ke perusahaan')
                            ->columnSpanFull(),
                        
                        Select::make('status_pembayaran')
                            ->options([
                                'Lunas' => 'Lunas',
                                'Belum Lunas' => 'Belum Lunas',
                            ])
                            ->default('Belum Lunas')
                            ->required()
                            ->helperText('Otomatis: Jika total pembayaran mencapai harga bersih, status akan otomatis menjadi Lunas')
                            ->disabled()
                            ->native(false)
                            ->label('Status Pembayaran')
                            ->live()
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),

                Section::make('Pembayaran')
                    ->schema([
                        TextInput::make('total_pembayaran')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing(fn ($state) => number_format($state, 0, '.', ',')),
                        TextInput::make('sisa_pembayaran')
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing(fn ($state) => number_format($state, 0, '.', ',')),
                        Repeater::make('dataPembayarans')
                            ->relationship()
                            ->collapsible()
                            ->reorderable()
                            ->itemLabel(function (array $state): ?string {
                                $nama = $state['nama_pembayar'] ?? 'Pembayaran Baru';
                                $nominal = $state['nominal'] ?? 0;
                                // Bersihkan format jika ada koma, lalu format ulang
                                $nominalValue = (int) str_replace(',', '', (string) $nominal);
                                return $nama . ' - Rp ' . number_format($nominalValue, 0, '.', ',');
                            })
                            ->schema([
                                TextInput::make('nama_pembayar')
                                    ->required()
                                    ->maxLength(255)
                                    ->label('Nama Pembayar'),
                                TextInput::make('nominal')
                                    ->required()
                                    ->prefix('Rp')
                                    ->default(0)
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->rule('numeric')
                                    ->label('Nominal')
                                    ->live(onBlur: true),
                                DatePicker::make('tanggal_bayar')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y')
                                    ->label('Tanggal Bayar'),
                                Select::make('metode_pembayaran')
                                    ->options([
                                        'transfer' => 'Transfer',
                                        'cash' => 'Cash',
                                        'qris' => 'QRIS',
                                        'cek' => 'Cek',
                                    ])
                                    ->required()
                                    ->label('Metode Pembayaran'),
                                Select::make('rekening_tujuan_id')
                                    ->relationship('rekeningTujuan', 'nama_bank')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_bank} - {$record->nomor_rekening} ({$record->nama_pemilik})")
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
                                    ->label('Termin Pembayaran'),
                                FileUpload::make('bukti_transfer')
                                    ->directory('bukti-transfer')
                                    ->image()
                                    ->openable()
                                    ->downloadable()
                                    ->label('Bukti Transfer'),
                                Textarea::make('keterangan')
                                    ->label('Keterangan'),
                            ])
                            ->columns(2)
                            ->columnSpanFull()
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Pembayaran'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
