<?php

namespace App\Filament\Resources\Vendors\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Informasi Vendor')
                    ->description('Data identitas dan kategori vendor (bukan data keikutsertaan expo)')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo Vendor')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('vendor-logos')
                            ->columnSpanFull(),

                        TextInput::make('nama_vendor')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Vendor/Usaha')
                            ->placeholder('Contoh: Catering Berkah Jaya')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state)))
                            ->columnSpan(1),

                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->label('Slug URL')
                            ->placeholder('nama-vendor')
                            ->columnSpan(1),

                        TextInput::make('nama_pendaftar')
                            ->maxLength(255)
                            ->label('Nama Pendaftar')
                            ->placeholder('Nama orang yang mendaftarkan vendor')
                            ->nullable()
                            ->columnSpan(1),

                        Select::make('jenis_usaha_id')
                            ->relationship('jenisUsaha', 'nama_jenis_usaha')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label('Jenis Usaha')
                            ->native(false)
                            ->columnSpan(1),

                        Select::make('user_id')
                            ->relationship(
                                'user',
                                'name',
                                modifyQueryUsing: fn (Builder $query) => $query->whereHas(
                                    'roles',
                                    fn ($q) => $q->where('name', 'customer')
                                )
                            )
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->label('Pemilik Akun (Customer)')
                            ->helperText('Hubungkan vendor dengan akun customer. Data booth/paket dikelola di Partisipasi Expo.')
                            ->nullable()
                            ->columnSpanFull(),
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
            ]);
    }
}
