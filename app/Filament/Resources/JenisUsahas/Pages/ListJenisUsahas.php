<?php

namespace App\Filament\Resources\JenisUsahas\Pages;

use App\Filament\Resources\JenisUsahas\JenisUsahaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJenisUsahas extends ListRecords
{
    protected static string $resource = JenisUsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
