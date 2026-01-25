<?php

namespace App\Filament\Widgets;

use App\Models\Partisipasi;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class LaporanPiutangStatsOverview extends BaseWidget
{
    public ?string $heading = 'Laporan Piutang';
    
    protected function getStats(): array
    {
        $query = Partisipasi::query()
            ->where('status_pembayaran', '!=', 'Lunas')
            ->where('sisa_pembayaran', '>', 0);

        $totalPiutang = $query->sum('sisa_pembayaran');
        $jumlahTenant = $query->count();
        $piutangTertinggi = $query->max('sisa_pembayaran');

        return [
            Stat::make('Total Piutang Tertunggak', '' . number_format($totalPiutang, 0, ',', '.'))
                ->description('Total tagihan yang belum dibayar')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('danger'),

            Stat::make('Jumlah Tenant Menunggak', $jumlahTenant . ' Tenant')
                ->description('Vendor yang belum lunas')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('warning'),

            Stat::make('Piutang Tertinggi', '' . number_format($piutangTertinggi, 0, ',', '.'))
                ->description('Nominal tunggakan terbesar')
                ->descriptionIcon(Heroicon::OutlinedExclamationCircle)
                ->color('danger'),
        ];
    }
}
