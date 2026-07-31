<?php

namespace App\Filament\Resources\PenyelenggaraGalleries\Pages;

use App\Filament\Resources\PenyelenggaraGalleries\PenyelenggaraGalleryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenyelenggaraGalleries extends ListRecords
{
    protected static string $resource = PenyelenggaraGalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
