<?php

namespace App\Filament\Resources\JenisUsahas\Pages;

use App\Filament\Resources\JenisUsahas\JenisUsahaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditJenisUsaha extends EditRecord
{
    protected static string $resource = JenisUsahaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
