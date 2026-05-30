<?php

namespace App\Filament\Resources\Doorprizes;

use UnitEnum;
use App\Filament\Resources\Doorprizes\Pages\CreateDoorprize;
use App\Filament\Resources\Doorprizes\Pages\EditDoorprize;
use App\Filament\Resources\Doorprizes\Pages\ListDoorprizes;
use App\Filament\Resources\Doorprizes\Schemas\DoorprizeForm;
use App\Filament\Resources\Doorprizes\Tables\DoorprizesTable;
use App\Models\Doorprize;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DoorprizeResource extends Resource
{
    protected static ?string $model = Doorprize::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

    protected static string|UnitEnum|null $navigationGroup = 'Expo';

    public static function form(Schema $schema): Schema
    {
        return DoorprizeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoorprizesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoorprizes::route('/'),
            'create' => CreateDoorprize::route('/create'),
            'edit' => EditDoorprize::route('/{record}/edit'),
        ];
    }
}
