<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Penyelenggara extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Penyelenggara';

    protected static ?string $slug = 'penyelenggara';

    protected static ?int $navigationSort = 3;
}
