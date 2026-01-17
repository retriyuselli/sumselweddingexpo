<?php

namespace App\Filament\Resources\Doorprizes\Pages;

use App\Filament\Resources\Doorprizes\DoorprizeResource;
use App\Filament\Resources\Doorprizes\Widgets\DoorprizeOverview;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDoorprizes extends ListRecords
{
    protected static string $resource = DoorprizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadForm')
                ->label('Download Form Tring! Pegadaian (PDF)')
                ->icon('heroicon-m-document-arrow-down')
                ->color('success')
                ->url(fn (): string => route('form.tring-pegadaian.pdf'))
                ->openUrlInNewTab(),
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            DoorprizeOverview::class,
        ];
    }
}
