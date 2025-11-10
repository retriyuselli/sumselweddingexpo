<?php

namespace App\Filament\Resources\CategoryTenants\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use App\Enums\CategoryTier;
use App\Models\Expo;
use Filament\Support\RawJs;
use Illuminate\Validation\Rule;

class CategoryTenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Tenant')
                    ->schema([
                        Select::make('expo_id')
                            ->relationship('expo', 'nama_expo')
                            ->getOptionLabelFromRecordUsing(fn (Expo $record) => $record->nama_expo.' ('.$record->periode.')')
                            ->searchable()
                            ->required()
                            ->label('Expo')
                            ->columnSpan(1),

                        Select::make('category')
                            ->options(CategoryTier::options())
                            ->required()
                            ->label('Kategori Tenant')
                            ->live(onBlur: true)
                            ->columnSpan(1),

                        TextInput::make('ukuran')
                            ->maxLength(255)
                            ->nullable()
                            ->placeholder('Contoh: 3x3 m')
                            ->label('Ukuran')
                            ->columnSpanFull(),

                        Textarea::make('deskripsi')
                            ->rows(3)
                            ->nullable()
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                    ]),

                Section::make('Harga & Unit')
                    ->columns(2)
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
                            ->live(onBlur: true)
                            ->columnSpan(1)
                            ->rules([
                                'required',
                                'integer',
                                'min:0',
                                function () {
                                    return function (string $attribute, $value, $fail) {
                                        $modal = request()->input('harga_modal');
                                        if (is_numeric($modal) && is_numeric($value) && (int) $value <= (int) $modal) {
                                            $fail('Harga jual harus lebih besar dari harga modal.');
                                        }
                                    };
                                },
                            ]),

                        TextInput::make('harga_modal')
                            ->integer()
                            ->prefix('Rp')
                            ->required()
                            ->minValue(0)
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->label('Harga Modal')
                            ->live(onBlur: true)
                            ->helperText(function (callable $get) {
                                $jual = $get('harga_jual');
                                $modal = $get('harga_modal');
                                
                                if (!is_numeric($jual) || !is_numeric($modal)) {
                                    return 'Biaya modal/pokok per unit.';
                                }
                                
                                $valid = (int) $jual > (int) $modal;
                                
                                if (!$valid) {
                                    return '⚠️ Peringatan: Harga modal harus lebih kecil dari harga jual!';
                                }
                                
                                $persentaseMargin = (((int) $jual - (int) $modal) / (int) $modal) * 100;
                                return '✓ Valid - Margin: '.number_format($persentaseMargin, 1).'%';
                            })
                            ->columnSpan(1),

                        TextInput::make('jumlah_unit')
                            ->integer()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->label('Jumlah Unit')
                            ->live(onBlur: true)
                            ->helperText(function (callable $get) {
                                $jual = $get('harga_jual');
                                $modal = $get('harga_modal');
                                $unit = $get('jumlah_unit');
                                
                                if (!is_numeric($jual) || !is_numeric($modal) || !is_numeric($unit) || (int) $unit < 1) {
                                    return 'Jumlah unit/booth yang tersedia.';
                                }
                                
                                $totalJual = (int) $jual * (int) $unit;
                                $totalModal = (int) $modal * (int) $unit;
                                $laba = $totalJual - $totalModal;
                                $fmt = fn ($v) => 'Rp '.number_format((int) $v, 0, ',', '.');
                                
                                return 'Total Pendapatan: '.$fmt($totalJual).' • Modal: '.$fmt($totalModal).' • Est. Laba: '.$fmt($laba);
                            })
                            ->columnSpan(1),
                    ]),

                Section::make('Status')
                    ->columns(1)
                    ->schema([
                        Toggle::make('status')
                            ->label('Aktif')
                            ->default(true)
                            ->inline(false)
                            ->required(),
                    ]),
            ]);
    }
}
