<?php

namespace App\Filament\Resources\Doorprizes\Pages;

use App\Filament\Resources\Doorprizes\DoorprizeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDoorprize extends CreateRecord
{
    protected static string $resource = DoorprizeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
