<?php

namespace App\Filament\Resources\DataPembayarans\Pages;

use App\Filament\Resources\DataPembayarans\DataPembayaranResource;
use App\Filament\Resources\DataPembayarans\Widgets\DataPembayaranOverview;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListDataPembayarans extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = DataPembayaranResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            DataPembayaranOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
