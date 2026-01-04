<?php

namespace App\Filament\Resources\Rundowns\Pages;

use App\Filament\Resources\Rundowns\RundownResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRundowns extends ListRecords
{
    protected static string $resource = RundownResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
