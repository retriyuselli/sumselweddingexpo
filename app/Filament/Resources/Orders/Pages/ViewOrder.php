<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh_status')
                ->label('Refresh Status')
                ->icon('heroicon-o-arrow-path')
                ->action(function ($record) {
                    $record->refresh();
                }),
        ];
    }
}