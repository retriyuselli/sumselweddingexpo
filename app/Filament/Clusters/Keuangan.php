<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Keuangan extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Keuangan';

    protected static ?string $slug = 'keuangan';

    protected static ?int $navigationSort = 7;
}
