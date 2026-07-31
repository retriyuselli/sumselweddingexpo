<?php

namespace App\Filament\Resources\Doorprizes\Pages;

use App\Filament\Resources\Doorprizes\DoorprizeResource;
use App\Filament\Resources\Doorprizes\Widgets\DoorprizeOverview;
use App\Models\Expo;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListDoorprizes extends ListRecords
{
    protected static string $resource = DoorprizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadLaporan')
                ->label('Download Laporan')
                ->icon('heroicon-o-document-chart-bar')
                ->color('info')
                ->form([
                    Select::make('expo_id')
                        ->label('Pilih Expo')
                        ->options(fn (): array => Expo::query()
                            ->orderByDesc('tanggal_mulai')
                            ->orderByDesc('id')
                            ->get()
                            ->mapWithKeys(fn (Expo $expo) => [$expo->id => $expo->labelForSelect()])
                            ->all())
                        ->searchable()
                        ->required()
                        ->helperText('Laporan PDF akan berisi data doorprize untuk expo yang dipilih.'),
                ])
                ->action(function (array $data) {
                    return redirect()->route('doorprizes.laporan', [
                        'expo' => $data['expo_id'],
                    ]);
                }),
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
