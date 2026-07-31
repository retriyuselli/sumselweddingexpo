<?php

namespace App\Filament\Resources\Rundowns\Pages;

use App\Filament\Resources\Rundowns\RundownResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRundown extends EditRecord
{
    protected static string $resource = RundownResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
