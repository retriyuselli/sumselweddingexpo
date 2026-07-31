<?php

namespace App\Filament\Resources\PengeluaranLains\Pages;

use App\Filament\Resources\PengeluaranLains\PengeluaranLainResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPengeluaranLain extends EditRecord
{
    protected static string $resource = PengeluaranLainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
