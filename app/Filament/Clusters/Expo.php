<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Expo extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Expo';

    protected static ?string $slug = 'expo';

    protected static ?int $navigationSort = 5;
}
