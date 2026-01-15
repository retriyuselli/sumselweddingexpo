<?php

namespace App\Filament\Resources\Doorprizes\Pages;

use App\Filament\Resources\Doorprizes\DoorprizeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDoorprize extends EditRecord
{
    protected static string $resource = DoorprizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
