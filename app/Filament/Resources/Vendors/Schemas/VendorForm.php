<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use App\Models\Expo;
use App\Models\CategoryTenant;
use App\Enums\CategoryTier;
use Illuminate\Support\Facades\DB;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Informasi Vendor')
                    ->description('Data identitas dan kategori vendor')
                    ->schema([
                        TextInput::make('nama_vendor')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Vendor/Usaha')
                            ->placeholder('Contoh: Catering Berkah Jaya')
                            ->columnSpan(1),

                        Select::make('jenis_usaha_id')
                            ->relationship('jenisUsaha', 'nama_jenis_usaha')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Jenis Usaha')
                            ->native(false)
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Lokasi & Kontak')
                    ->description('Alamat dan informasi kontak vendor')
                    ->schema([
                        Textarea::make('alamat')
                            ->required()
                            ->rows(3)
                            ->label('Alamat Lengkap')
                            ->placeholder('Jalan, Nomor, RT/RW, Kelurahan, Kecamatan')
                            ->columnSpanFull(),

                        TextInput::make('kota')
                            ->required()
                            ->maxLength(255)
                            ->label('Kota/Kabupaten')
                            ->placeholder('Contoh: Palembang')
                            ->columnSpan(1),

                        TextInput::make('no_telepon')
                            ->tel()
                            ->required()
                            ->maxLength(30)
                            ->label('No. Telepon')
                            ->placeholder('Contoh: 0711-123456')
                            ->prefix('📞')
                            ->columnSpan(1),

                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->label('Email')
                            ->placeholder('vendor@example.com')
                            ->prefix('✉️')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Person In Charge (PIC)')
                    ->description('Informasi kontak person yang dapat dihubungi')
                    ->schema([
                        TextInput::make('nama_pic')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama PIC')
                            ->placeholder('Nama lengkap person in charge')
                            ->columnSpan(1),

                        TextInput::make('no_wa_pic')
                            ->tel()
                            ->maxLength(30)
                            ->label('No. WhatsApp PIC')
                            ->placeholder('Contoh: 081234567890')
                            ->prefix('📱')
                            ->helperText('Format: 08xxxxxxxxxx (tanpa +62)')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Preferensi Booth')
                    ->description('Informasi paket dan lokasi booth yang diminati')
                    ->schema([
                        Select::make('paket')
                            ->label('Paket/Kategori berdasarkan Expo terdekat')
                            ->options(function () {
                                $nearestExpo = Expo::where('status', true)
                                    ->whereDate('tanggal_mulai', '>=', now())
                                    ->orderBy('tanggal_mulai')
                                    ->first();

                                if (!$nearestExpo) {
                                    $nearestExpo = Expo::where('status', true)
                                        ->orderBy('tanggal_mulai', 'desc')
                                        ->first();
                                }

                                if (!$nearestExpo) {
                                    return [];
                                }

                                // Ambil nilai kategori sebagai string mentah dari DB agar tidak terkena casting enum
                                $categories = DB::table('category_tenants')
                                    ->where('expo_id', $nearestExpo->id)
                                    ->where('status', true)
                                    ->distinct()
                                    ->pluck('category')
                                    ->toArray();

                                return collect($categories)
                                    ->mapWithKeys(function ($value) {
                                        $tier = CategoryTier::tryFrom($value);
                                        return [$value => $tier ? $tier->label() : $value];
                                    })
                                    ->toArray();
                            })
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $nearestExpo = Expo::where('status', true)
                                    ->whereDate('tanggal_mulai', '>=', now())
                                    ->orderBy('tanggal_mulai')
                                    ->first()
                                    ?? Expo::where('status', true)->orderBy('tanggal_mulai', 'desc')->first();

                                if (!$nearestExpo || !$state) {
                                    $set('harga_jual', null);
                                    return;
                                }

                                $price = DB::table('category_tenants')
                                    ->where('expo_id', $nearestExpo->id)
                                    ->where('status', true)
                                    ->where('category', $state)
                                    ->value('harga_jual');

                                $set('harga_jual', is_numeric($price) ? (int) $price : null);
                            })
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih kategori dari Expo terdekat')
                            ->helperText(function () {
                                $expo = Expo::where('status', true)
                                    ->whereDate('tanggal_mulai', '>=', now())
                                    ->orderBy('tanggal_mulai')
                                    ->first()
                                    ?? Expo::where('status', true)->orderBy('tanggal_mulai', 'desc')->first();

                                return $expo
                                    ? ('Opsi diambil dari: ' . $expo->nama_expo . ' (' . ($expo->periode ?? '-') . ')')
                                    : 'Belum ada expo aktif';
                            })
                            ->columnSpan(1),

                        TextInput::make('harga_jual')
                            ->label('Harga Paket')
                            ->disabled()
                            ->dehydrated(true)
                            ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp ' . number_format((int) $state, 0, ',', '.'))
                            ->helperText('Otomatis mengikuti kategori pada expo terdekat.')
                            ->columnSpan(1),

                        TextInput::make('lokasi_booth')
                            ->maxLength(100)
                            ->label('Lokasi Booth')
                            ->placeholder('Contoh: Hall A, Blok B12')
                            ->nullable()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
