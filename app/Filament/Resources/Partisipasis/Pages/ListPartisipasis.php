<?php

namespace App\Filament\Resources\Partisipasis\Pages;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use App\Filament\Resources\Partisipasis\Widgets\StatistikWidget;
use App\Models\Expo;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ListRecords;

class ListPartisipasis extends ListRecords
{
    protected static string $resource = PartisipasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->form([
                    Select::make('expo_id')
                        ->label('Pilih Expo')
                        ->options(function () {
                            return Expo::query()
                                ->orderByDesc('tanggal_mulai')
                                ->get()
                                ->mapWithKeys(function (Expo $expo) {
                                    $parts = array_filter([
                                        $expo->periode,
                                        $expo->tanggal_mulai?->format('d M Y'),
                                    ]);

                                    $suffix = $parts !== [] ? ' ('.implode(' · ', $parts).')' : '';

                                    return [$expo->id => $expo->nama_expo.$suffix];
                                })
                                ->all();
                        })
                        ->searchable()
                        ->required()
                        ->helperText('Pilih expo yang datanya ingin diunduh.'),
                    Toggle::make('only_active')
                        ->label('Hanya partisipasi aktif')
                        ->default(false)
                        ->helperText('Aktifkan jika ingin mengekspor hanya yang tampil di halaman peserta.'),
                ])
                ->action(function (array $data) {
                    return redirect()->route('partisipasis.pdf', [
                        'expo' => $data['expo_id'],
                        'only_active' => ! empty($data['only_active']) ? 1 : 0,
                    ]);
                }),
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
