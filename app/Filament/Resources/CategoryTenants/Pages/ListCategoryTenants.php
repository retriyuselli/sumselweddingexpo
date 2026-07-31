<?php

namespace App\Filament\Resources\CategoryTenants\Pages;

use App\Filament\Resources\CategoryTenants\CategoryTenantResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCategoryTenants extends ListRecords
{
    protected static string $resource = CategoryTenantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
