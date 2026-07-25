<?php

namespace App\Filament\Resources\Doorprizes\Schemas;

use App\Models\Doorprize;
use App\Models\Expo;
use App\Models\Partisipasi;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class DoorprizeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->tabs([
                        Tab::make('Informasi Umum')
                            ->icon('heroicon-m-user')
                            ->schema([
                                Section::make('Detail Undian')
                                    ->description('Informasi voucher dan tenant')
                                    ->schema([
                                        Select::make('expo_id')
                                            ->label('Pilih Expo')
                                            ->options(Expo::all()->pluck('nama_expo', 'id'))
                                            ->live()
                                            ->afterStateUpdated(fn (callable $set) => $set('partisipasi_id', null))
                                            ->afterStateHydrated(fn (Select $component, ?Doorprize $record) => $component->state($record?->partisipasi?->expo_id))
                                            ->dehydrated(false)
                                            ->required()
                                            ->prefixIcon('heroicon-o-calendar'),
                                        Select::make('partisipasi_id')
                                            ->label('Tenant / Vendor')
                                            ->options(fn (callable $get) => Partisipasi::query()
                                                ->where('expo_id', $get('expo_id'))
                                                ->with('vendor')
                                                ->get()
                                                ->pluck('vendor.nama_vendor', 'id'))
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->prefixIcon('heroicon-o-building-storefront'),
                                    ])->columns(1),
                                Section::make('Informasi Pemenang')
                                    ->description('Data diri pemenang doorprize')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nama Lengkap')
                                            ->required()
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-user'),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-envelope'),
                                        TextInput::make('no_wa')
                                            ->label('No. WhatsApp')
                                            ->tel()
                                            ->numeric()
                                            ->required()
                                            ->maxLength(15)
                                            ->prefixIcon('heroicon-o-phone'),
                                        TextInput::make('nik')
                                            ->label('NIK')
                                            ->numeric()
                                            ->prefixIcon('heroicon-o-identification'),
                                        TextInput::make('kodevoucher')
                                            ->label('Kode Voucher')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->prefixIcon('heroicon-o-ticket')
                                            ->helperText('Kode voucher harus unik.')
                                            ->dehydrateStateUsing(fn (string $state): string => strtoupper($state)),
                                        Textarea::make('alamat')
                                            ->label('Alamat Domisili')
                                            ->required()
                                            ->default('Palembang')
                                            ->columnSpanFull()
                                            ->rows(3),
                                        TextInput::make('provinsi')
                                            ->label('Provinsi')
                                            ->default('Sumatera Selatan')
                                            ->maxLength(255),
                                        FileUpload::make('foto_ktp')
                                            ->label('Foto KTP')
                                            ->image()
                                            ->disk('local')
                                            ->visibility('private')
                                            ->directory('doorprizes/ktp')
                                            ->columnSpanFull(),
                                        FileUpload::make('surat_pernyataan')
                                            ->label('Surat Pernyataan')
                                            ->disk('local')
                                            ->visibility('private')
                                            ->directory('doorprizes/surat-pernyataan')
                                            ->openable()
                                            ->columnSpanFull(),
                                        Toggle::make('sudah_download_tring')
                                            ->label('Apakah sudah download aplikasi Tring! Pegadaian?')
                                            ->default(false)
                                            ->columnSpanFull(),
                                    ])->columns(2),
                            ]),

                        Tab::make('Riwayat Transaksi')
                            ->icon('heroicon-m-banknotes')
                            ->schema([
                                Group::make()
                                    ->schema([
                                        Placeholder::make('total_nom_trx')
                                            ->label('Total Nominal Transaksi')
                                            ->content(fn (Get $get) => Doorprize::formatRupiah(
                                                Doorprize::sumTransactionField($get('transactions'), 'nom_trx')
                                            )),
                                        Placeholder::make('total_no_rev')
                                            ->label('Total Nominal Revenue')
                                            ->content(fn (Get $get) => Doorprize::formatRupiah(
                                                Doorprize::sumTransactionField($get('transactions'), 'no_rev')
                                            )),
                                    ])->columns(2),
                                Repeater::make('transactions')
                                    ->label('Detail Transaksi')
                                    ->hiddenLabel()
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name_package'] ?? null)
                                    ->cloneable()
                                    ->schema([
                                        TextInput::make('name_package')
                                            ->label('Nama Paket')
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('nom_trx')
                                            ->label('Nominal Transaksi')
                                            ->prefix('Rp')
                                            ->minValue(Doorprize::MIN_NOMINAL_TRANSAKSI)
                                            ->helperText('Minimal transaksi '.Doorprize::formatRupiah(Doorprize::MIN_NOMINAL_TRANSAKSI))
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->numeric()
                                            ->rules(['integer', 'min:'.Doorprize::MIN_NOMINAL_TRANSAKSI])
                                            ->validationMessages([
                                                'min' => 'Nominal transaksi minimal '.Doorprize::formatRupiah(Doorprize::MIN_NOMINAL_TRANSAKSI).'.',
                                            ])
                                            ->live(debounce: 500)
                                            ->required(),
                                        TextInput::make('no_rev')
                                            ->label('Nominal Revenue')
                                            ->prefix('Rp')
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->numeric()
                                            ->rules(['integer', 'min:0'])
                                            ->live(debounce: 500)
                                            ->required(),
                                        FileUpload::make('bukti_trx')
                                            ->label('Bukti Transaksi')
                                            ->image()
                                            ->disk('local')
                                            ->visibility('private')
                                            ->directory('doorprizes/transactions')
                                            ->required()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ]),
                            
                    ])->columnSpanFull(),
            ]);
    }
}
