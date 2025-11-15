<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Clusters\Keuangan as KeuanganCluster;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Pages\ViewOrder;
use App\Filament\Resources\Orders\Schemas\OrderInfolist;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = null;

    protected static ?string $cluster = KeuanganCluster::class;

    protected static ?string $navigationLabel = 'Orders';

    protected static ?string $modelLabel = 'Order';

    protected static ?string $pluralModelLabel = 'Orders';

    public static function infolist(Schema $schema): Schema
    {
        return OrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }
}