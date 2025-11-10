<?php

namespace App\Filament\Resources\CategoryTenants;

use App\Filament\Resources\CategoryTenants\Pages\CreateCategoryTenant;
use App\Filament\Resources\CategoryTenants\Pages\EditCategoryTenant;
use App\Filament\Resources\CategoryTenants\Pages\ListCategoryTenants;
use App\Filament\Resources\CategoryTenants\Schemas\CategoryTenantForm;
use App\Filament\Resources\CategoryTenants\Tables\CategoryTenantsTable;
use App\Models\CategoryTenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryTenantResource extends Resource
{
    protected static ?string $model = CategoryTenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    public static function form(Schema $schema): Schema
    {
        return CategoryTenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoryTenantsTable::configure($table);
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
            'index' => ListCategoryTenants::route('/'),
            'create' => CreateCategoryTenant::route('/create'),
            'edit' => EditCategoryTenant::route('/{record}/edit'),
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
