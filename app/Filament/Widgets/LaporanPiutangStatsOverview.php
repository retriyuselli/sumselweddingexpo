<?php

namespace App\Filament\Widgets;

use App\Models\Partisipasi;
use App\Services\LabaRugiAggregator;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LaporanPiutangStatsOverview extends BaseWidget
{
    public ?string $heading = 'Laporan Piutang';

    /** Only mounted via LaporanPiutang::getHeaderWidgets() */
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        $query = Partisipasi::query()->where('sisa_pembayaran', '>', 0);

        $totalPiutang = (float) (clone $query)->sum('sisa_pembayaran');
        $jumlahTenant = (clone $query)->count();
        $piutangTertinggi = (float) ((clone $query)->max('sisa_pembayaran') ?? 0);

        return [
            Stat::make('Total Piutang Tertunggak', LabaRugiAggregator::formatRupiah($totalPiutang))
                ->description('Total tagihan yang belum dibayar')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('danger'),

            Stat::make('Jumlah Tenant Menunggak', $jumlahTenant.' Tenant')
                ->description('Vendor yang belum lunas')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('warning'),

            Stat::make('Piutang Tertinggi', LabaRugiAggregator::formatRupiah($piutangTertinggi))
                ->description('Nominal tunggakan terbesar')
                ->descriptionIcon(Heroicon::OutlinedExclamationCircle)
                ->color('danger'),
        ];
    }
}
