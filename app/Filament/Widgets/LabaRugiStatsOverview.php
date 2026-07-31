<?php

namespace App\Filament\Widgets;

use App\Services\LabaRugiAggregator;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LabaRugiStatsOverview extends BaseWidget
{
    public ?string $heading = 'Data Laba Rugi';

    /** Only mounted via LabaRugiReport::getHeaderWidgets() */
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $totals = app(LabaRugiAggregator::class)->globalTotals();

        return [
            Stat::make('Total Pemasukan', LabaRugiAggregator::formatRupiah($totals['pemasukan']))
                ->icon(Heroicon::OutlinedArrowTrendingUp)
                ->color('success'),

            Stat::make('Total Pengeluaran', LabaRugiAggregator::formatRupiah($totals['pengeluaran']))
                ->icon(Heroicon::OutlinedArrowTrendingDown)
                ->color('danger'),

            Stat::make('Total Piutang', LabaRugiAggregator::formatRupiah($totals['piutang']))
                ->icon(Heroicon::OutlinedExclamationCircle)
                ->description('Potensi pemasukan tertunda')
                ->color('warning'),

            Stat::make('Laba Bersih', LabaRugiAggregator::formatRupiah($totals['laba_rugi']))
                ->icon(Heroicon::OutlinedBanknotes)
                ->description('Berdasarkan uang masuk (Cash Basis)')
                ->color($totals['laba_rugi'] >= 0 ? 'primary' : 'danger'),
        ];
    }
}
