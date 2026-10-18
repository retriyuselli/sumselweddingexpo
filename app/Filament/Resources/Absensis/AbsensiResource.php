<?php

namespace App\Filament\Resources\Absensis;

use App\Filament\Resources\Absensis\Pages\ListAbsensis;
use App\Filament\Resources\Absensis\Tables\AbsensisTable;
use App\Models\Absensi;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AbsensiResource extends Resource
{
    protected static ?string $model = Absensi::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQrCode;

    protected static string|UnitEnum|null $navigationGroup = 'Expo';

    protected static ?string $navigationLabel = 'Absensi';

    protected static ?string $modelLabel = 'Absensi tamu';

    protected static ?string $pluralModelLabel = 'Absensi tamu';

    public static function table(Table $table): Table
    {
        return AbsensisTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAbsensis::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
