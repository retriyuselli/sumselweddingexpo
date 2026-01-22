<?php

namespace App\Filament\Resources\Pengeluarans\Widgets;

use App\Models\Pengeluaran;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PengeluaranOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pengeluaran', '' . number_format(Pengeluaran::sum('nominal'), 0, ',', '.')),
            Stat::make('Jumlah Transaksi', Pengeluaran::count()),
        ];
    }
}
