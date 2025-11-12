<?php

namespace App\Filament\Resources\Penyelenggaras\Pages;

use App\Filament\Resources\Penyelenggaras\PenyelenggaraResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditPenyelenggara extends EditRecord
{
    protected static string $resource = PenyelenggaraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
