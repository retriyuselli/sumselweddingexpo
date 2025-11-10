<?php

namespace App\Filament\Resources\CategoryTenants\Pages;

use App\Filament\Resources\CategoryTenants\CategoryTenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoryTenant extends CreateRecord
{
    protected static string $resource = CategoryTenantResource::class;
}
