<?php

namespace App\Filament\Resources\TenantSpots\Pages;

use App\Filament\Resources\TenantSpots\TenantSpotResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTenantSpot extends EditRecord
{
    protected static string $resource = TenantSpotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
