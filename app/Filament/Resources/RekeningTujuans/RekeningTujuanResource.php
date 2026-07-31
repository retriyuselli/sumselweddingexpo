<?php

namespace App\Filament\Resources\RekeningTujuans;

use App\Filament\Resources\RekeningTujuans\Pages\CreateRekeningTujuan;
use App\Filament\Resources\RekeningTujuans\Pages\EditRekeningTujuan;
use App\Filament\Resources\RekeningTujuans\Pages\ListRekeningTujuans;
use App\Filament\Resources\RekeningTujuans\Schemas\RekeningTujuanForm;
use App\Filament\Resources\RekeningTujuans\Tables\RekeningTujuansTable;
use App\Models\RekeningTujuan;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekeningTujuanResource extends Resource
{
    protected static ?string $model = RekeningTujuan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    public static function form(Schema $schema): Schema
    {
        return RekeningTujuanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekeningTujuansTable::configure($table);
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
            'index' => ListRekeningTujuans::route('/'),
            'create' => CreateRekeningTujuan::route('/create'),
            'edit' => EditRekeningTujuan::route('/{record}/edit'),
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
