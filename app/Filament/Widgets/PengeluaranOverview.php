<?php

namespace App\Filament\Widgets;

use App\Models\Pengeluaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class PengeluaranOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    public ?string $heading = 'Data Pengeluaran';

    /** Registered explicitly on Dashboard — avoid duplicate discovery */
    protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $expoId = $this->filters['expo_id'] ?? null;

        $query = Pengeluaran::query();

        if ($expoId) {
            $query->where('expo_id', $expoId);
        }

        if ($startDate) {
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal', '<=', $endDate);
        }

        return [
            Stat::make('Total Pengeluaran', '' . number_format($query->sum('nominal'), 0, ',', '.'))
                ->description('Total nominal pengeluaran')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
            
            Stat::make('Jumlah Transaksi', $query->count())
                ->description('Total transaksi pengeluaran')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('primary'),

            Stat::make('Pengeluaran Hari Ini', '' . number_format(Pengeluaran::whereDate('tanggal', today())->sum('nominal'), 0, ',', '.'))
                ->description('Total pengeluaran tanggal ' . today()->format('d M Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('warning'),
        ];
    }
}
