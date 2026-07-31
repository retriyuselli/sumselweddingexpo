<?php

namespace App\Filament\Resources\Doorprizes\Pages;

use App\Filament\Resources\Doorprizes\DoorprizeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDoorprize extends EditRecord
{
    protected static string $resource = DoorprizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadForm')
                ->label('Lihat / Cetak Form TRING')
                ->icon('heroicon-m-arrow-top-right-on-square')
                ->color('success')
                ->url(fn (): string => route('doorprizes.form-tring-pegadaian.pdf', $this->getRecord()))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
