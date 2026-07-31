<?php

namespace App\Filament\Resources\ProductVendors;

use UnitEnum;
use App\Filament\Resources\ProductVendors\Pages\CreateProductVendor;
use App\Filament\Resources\ProductVendors\Pages\EditProductVendor;
use App\Filament\Resources\ProductVendors\Pages\ListProductVendors;
use App\Filament\Resources\ProductVendors\Schemas\ProductVendorForm;
use App\Filament\Resources\ProductVendors\Tables\ProductVendorsTable;
use App\Models\ProductVendor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductVendorResource extends Resource
{
    protected static ?string $model = ProductVendor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static string|UnitEnum|null $navigationGroup = 'Vendor';

    public static function form(Schema $schema): Schema
    {
        return ProductVendorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVendorsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductVendors::route('/'),
            'create' => CreateProductVendor::route('/create'),
            'edit' => EditProductVendor::route('/{record}/edit'),
        ];
    }
}