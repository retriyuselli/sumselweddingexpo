<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Vendor extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $navigationLabel = 'Vendor';

    protected static ?string $slug = 'vendor';

    protected static ?int $navigationSort = 6;
}
