<?php

namespace App\Filament\Resources\Partisipasis\Schemas;

use App\Models\CategoryTenant;
use App\Models\Expo;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $categoryTenant = CategoryTenant::find($state);
                                    if ($categoryTenant) {
                                        $set('harga_jual', $categoryTenant->harga_jual);
                                    }
                                }
                            }),

                        TextInput::make('blok_tenant')
                            ->maxLength(255)
                            ->label('Blok/Nomor Tenant')
                            ->placeholder('Contoh: A-01, B-12')
                            ->nullable()
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Pembayaran')
                    ->schema([
                        TextInput::make('harga_jual')
                            ->integer()
                            ->prefix('Rp')
                            ->required()
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->label('Harga Jual')
                            ->helperText('Harga akan otomatis terisi sesuai kategori tenant')
                            ->columnSpan(1),

                        Select::make('status_pembayaran')
                            ->options([
                                'lunas' => 'Lunas',
                                'belum_lunas' => 'Belum Lunas',
                                'dp' => 'DP (Down Payment)',
                                'cicilan' => 'Cicilan',
                            ])
                            ->default('belum_lunas')
                            ->required()
                            ->native(false)
                            ->label('Status Pembayaran')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
