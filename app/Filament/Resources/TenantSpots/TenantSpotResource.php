<?php

namespace App\Filament\Resources\TenantSpots;

use UnitEnum;
use App\Filament\Resources\TenantSpots\Pages\CreateTenantSpot;
use App\Filament\Resources\TenantSpots\Pages\EditTenantSpot;
use App\Filament\Resources\TenantSpots\Pages\ListTenantSpots;
use App\Filament\Resources\TenantSpots\Schemas\TenantSpotForm;
use App\Filament\Resources\TenantSpots\Tables\TenantSpotsTable;
use App\Models\TenantSpot;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TenantSpotResource extends Resource
{
    protected static ?string $model = TenantSpot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?string $navigationLabel = 'Spot Tenant';

    protected static ?string $modelLabel = 'Spot Tenant';

    protected static ?string $pluralModelLabel = 'Spot Tenant';

    protected static string|UnitEnum|null $navigationGroup = 'Expo';

    public static function form(Schema $schema): Schema
    {
        return TenantSpotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantSpotsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListTenantSpots::route('/'),
            'create' => CreateTenantSpot::route('/create'),
            'edit'   => EditTenantSpot::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
