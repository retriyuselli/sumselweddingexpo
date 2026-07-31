<?php

namespace App\Filament\Resources\ProductVendors\Pages;

use App\Filament\Resources\ProductVendors\ProductVendorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVendor extends CreateRecord
{
    protected static string $resource = ProductVendorResource::class;
}