<?php

namespace App\Filament\Resources\Expos;

use App\Filament\Clusters\Expo as ExpoCluster;
use App\Filament\Resources\Expos\Pages\CreateExpo;
use App\Filament\Resources\Expos\Pages\EditExpo;
use App\Filament\Resources\Expos\Pages\ListExpos;
use App\Filament\Resources\Expos\Schemas\ExpoForm;
use App\Filament\Resources\Expos\Tables\ExposTable;
use App\Models\Expo;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpoResource extends Resource
{
    protected static ?string $model = Expo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $cluster = ExpoCluster::class;

    public static function form(Schema $schema): Schema
    {
        return ExpoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExposTable::configure($table);
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
            'index' => ListExpos::route('/'),
            'create' => CreateExpo::route('/create'),
            'edit' => EditExpo::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
