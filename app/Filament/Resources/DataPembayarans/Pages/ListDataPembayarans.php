<?php

namespace App\Filament\Resources\DataPembayarans\Pages;

use App\Filament\Resources\DataPembayarans\DataPembayaranResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDataPembayarans extends ListRecords
{
    protected static string $resource = DataPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
