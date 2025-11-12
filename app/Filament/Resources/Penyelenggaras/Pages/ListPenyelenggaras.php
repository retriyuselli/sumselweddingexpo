<?php

namespace App\Filament\Resources\Penyelenggaras\Pages;

use App\Filament\Resources\Penyelenggaras\PenyelenggaraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenyelenggaras extends ListRecords
{
    protected static string $resource = PenyelenggaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
