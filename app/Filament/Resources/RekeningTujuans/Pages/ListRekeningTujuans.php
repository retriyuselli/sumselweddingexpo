<?php

namespace App\Filament\Resources\RekeningTujuans\Pages;

use App\Filament\Resources\RekeningTujuans\RekeningTujuanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRekeningTujuans extends ListRecords
{
    protected static string $resource = RekeningTujuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
