<?php

namespace App\Filament\Resources\CategoryTenants\Pages;

use App\Filament\Resources\CategoryTenants\CategoryTenantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditCategoryTenant extends EditRecord
{
    protected static string $resource = CategoryTenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
