<?php

namespace App\Filament\Resources\Partisipasis\Pages;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartisipasis extends ListRecords
{
    protected static string $resource = PartisipasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
