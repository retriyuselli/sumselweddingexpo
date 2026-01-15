<?php

namespace App\Filament\Resources\Partisipasis\Pages;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use App\Filament\Resources\Partisipasis\Widgets\StatistikWidget;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartisipasis extends ListRecords
{
    protected static string $resource = PartisipasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatistikWidget::class,
        ];
    }
}
