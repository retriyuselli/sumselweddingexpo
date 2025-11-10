<?php

namespace App\Filament\Resources\Partisipasis\Pages;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPartisipasi extends EditRecord
{
    protected static string $resource = PartisipasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
