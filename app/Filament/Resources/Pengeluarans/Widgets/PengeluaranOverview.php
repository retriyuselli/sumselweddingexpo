<?php

namespace App\Filament\Resources\Pengeluarans\Widgets;

use App\Filament\Resources\Pengeluarans\Pages\ListPengeluarans;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class PengeluaranOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListPengeluarans::class;
    }

    protected function getStats(): array
    {
        $baseQuery = $this->getPageTableQuery();

        $totalNominal = (clone $baseQuery)->sum('nominal');
        $totalCount = (clone $baseQuery)->count();
        $tanpaBukti = (clone $baseQuery)
            ->where(fn (Builder $query) => $query
                ->whereNull('bukti_transfer')
                ->orWhere('bukti_transfer', ''))
            ->count();
        $tanpaNotaDinas = (clone $baseQuery)
            ->where(fn (Builder $query) => $query
                ->whereNull('nota_dinas')
                ->orWhere('nota_dinas', ''))
            ->count();

        return [
            Stat::make('Total Pengeluaran', number_format((int) $totalNominal, 0, ',', '.')),
            Stat::make('Jumlah Transaksi', number_format($totalCount, 0, ',', '.')),
            Stat::make('Tanpa Bukti Transfer', number_format($tanpaBukti, 0, ',', '.'))
                ->color('danger'),
            Stat::make('Tanpa Nota Dinas', number_format($tanpaNotaDinas, 0, ',', '.'))
                ->color('warning'),
        ];
    }
}
