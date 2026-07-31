<?php

namespace App\Filament\Resources\RekeningTujuans\Pages;

use App\Filament\Resources\RekeningTujuans\RekeningTujuanResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRekeningTujuan extends EditRecord
{
    protected static string $resource = RekeningTujuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
