<?php

namespace App\Filament\Resources\ProductVendors\Pages;

use App\Filament\Resources\ProductVendors\ProductVendorResource;
use Filament\Resources\Pages\ListRecords;

class ListProductVendors extends ListRecords
{
    protected static string $resource = ProductVendorResource::class;
}