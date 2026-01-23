<?php

namespace App\Filament\Resources\DataPembayarans\Widgets;

use App\Models\DataPembayaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DataPembayaranOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pembayaran', '' . number_format(DataPembayaran::sum('nominal'), 0, ',', '.'))
                ->description('Total semua pembayaran yang masuk')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            Stat::make('Jumlah Transaksi', DataPembayaran::count())
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
