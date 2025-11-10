<?php

namespace App\Filament\Resources\Expos\Pages;

use App\Filament\Resources\Expos\ExpoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpos extends ListRecords
{
    protected static string $resource = ExpoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
