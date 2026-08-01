<?php

namespace App\Filament\Resources\DataPembayarans\Widgets;

use App\Filament\Resources\DataPembayarans\Pages\ListDataPembayarans;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class DataPembayaranOverview extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected static bool $isLazy = false;

    protected function getTablePage(): string
    {
        return ListDataPembayarans::class;
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
        $hariIni = (clone $baseQuery)
            ->whereDate('tanggal_bayar', today())
            ->sum('nominal');

        return [
            Stat::make('Total Pembayaran', ''.number_format((int) $totalNominal, 0, ',', '.'))
                ->description('Total nominal sesuai filter tabel')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Jumlah Transaksi', number_format($totalCount, 0, ',', '.'))
                ->description('Transaksi tercatat sesuai filter')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->color('primary'),

            Stat::make('Tanpa Bukti Transfer', number_format($tanpaBukti, 0, ',', '.'))
                ->description('Belum dilengkapi bukti')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Pembayaran Hari Ini', ''.number_format((int) $hariIni, 0, ',', '.'))
                ->description('Tanggal bayar '.today()->format('d M Y').' (ikut filter)')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),
        ];
    }
}
