<?php

namespace App\Filament\Resources\Homes;

use UnitEnum;
use App\Filament\Resources\Homes\Pages\CreateHome;
use App\Filament\Resources\Homes\Pages\EditHome;
use App\Filament\Resources\Homes\Pages\ListHomes;
use App\Filament\Resources\Homes\Schemas\HomeForm;
use App\Filament\Resources\Homes\Tables\HomesTable;
use App\Models\Home;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class HomeResource extends Resource
{
    protected static ?string $model = Home::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    public static function form(Schema $schema): Schema
    {
        return HomeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomesTable::configure($table);
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
            'index' => ListHomes::route('/'),
            'create' => CreateHome::route('/create'),
            'edit' => EditHome::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasRole('super_admin') === true;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasRole('super_admin') === true;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->hasRole('super_admin') === true;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->hasRole('super_admin') === true;
    }
}
