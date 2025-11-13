<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Pengguna extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $slug = 'pengguna';

    protected static ?int $navigationSort = 8;
}
