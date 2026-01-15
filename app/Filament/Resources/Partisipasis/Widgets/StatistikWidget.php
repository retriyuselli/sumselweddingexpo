<?php

namespace App\Filament\Resources\Partisipasis\Widgets;

use App\Models\Partisipasi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;


class StatistikWidget extends StatsOverviewWidget
{

    protected ?string $heading = 'Statistik Partisipasi';

    protected function getStats(): array
    {
        $total = Partisipasi::count();

        $lunas = Partisipasi::where('status_pembayaran', 'lunas')->count();

        $belumLunas = Partisipasi::where('status_pembayaran', '!=', 'lunas')->count();

        return [
            Stat::make('Total Partisipasi', (string) $total)
                ->description('Jumlah seluruh partisipasi')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Lunas', (string) $lunas)
                ->description('Pembayaran sudah lunas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Belum Lunas', (string) $belumLunas)
                ->description('Masih menunggu pelunasan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
        ];
    }
}
