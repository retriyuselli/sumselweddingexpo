<?php

namespace App\Filament\Widgets;

use App\Models\DataPembayaran;
use App\Models\Partisipasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class DataPembayaranOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $expoId = $this->filters['expo_id'] ?? null;

        $query = DataPembayaran::query();
        $partisipasiQuery = Partisipasi::query();

        if ($expoId) {
            $query->whereHas('partisipasi', function ($q) use ($expoId) {
                $q->where('expo_id', $expoId);
            });
            $partisipasiQuery->where('expo_id', $expoId);
        }

        if ($startDate) {
            $query->whereDate('tanggal_bayar', '>=', $startDate);
            $partisipasiQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('tanggal_bayar', '<=', $endDate);
            $partisipasiQuery->whereDate('created_at', '<=', $endDate);
        }

        return [
            Stat::make('Total Pembayaran', '' . number_format($query->sum('nominal'), 0, ',', '.'))
                ->description('Total semua pembayaran yang masuk')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            
            Stat::make('Sudah Lunas', (clone $partisipasiQuery)->where('status_pembayaran', 'Lunas')->count())
                ->description('Total partisipasi lunas')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Belum Lunas', (clone $partisipasiQuery)->where('status_pembayaran', 'Belum Lunas')->count())
                ->description('Total partisipasi belum lunas')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('Nominal Belum Lunas', 'Rp ' . number_format((clone $partisipasiQuery)->where('status_pembayaran', 'Belum Lunas')->sum('sisa_pembayaran'), 0, ',', '.'))
                ->description('Total sisa tagihan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),

            Stat::make('Jumlah Transaksi', $query->count())
                ->description('Total transaksi tercatat')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('primary'),
                
            Stat::make('Pembayaran Hari Ini', '' . number_format(DataPembayaran::whereDate('tanggal_bayar', today())->sum('nominal'), 0, ',', '.'))
                ->description('Total pembayaran tanggal ' . today()->format('d M Y'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
