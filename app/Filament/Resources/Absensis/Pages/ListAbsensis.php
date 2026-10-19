<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use App\Filament\Resources\Absensis\Widgets\AbsensiOverview;
use App\Filament\Resources\Absensis\Widgets\AbsensiTujuanChart;
use Filament\Resources\Pages\ListRecords;

class ListAbsensis extends ListRecords
{
    protected static string $resource = AbsensiResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            AbsensiOverview::class,
            AbsensiTujuanChart::class,
        ];
    }
}
