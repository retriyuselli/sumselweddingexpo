<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Sponsors extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationLabel = 'Sponsors';

    protected static ?string $slug = 'sponsors';

    protected static ?int $navigationSort = 4;
}
