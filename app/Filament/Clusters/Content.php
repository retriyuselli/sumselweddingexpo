<?php

namespace App\Filament\Clusters;

use BackedEnum;
use Filament\Clusters\Cluster;

class Content extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Konten';

    protected static ?string $slug = 'konten';

    protected static ?int $navigationSort = 2;
}
