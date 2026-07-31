<?php

namespace App\Filament\Resources\Doorprizes\Widgets;

use App\Models\Doorprize;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DoorprizeOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Doorprize::query()->count();
        $sudahDownload = Doorprize::query()->where('sudah_download_tring', true)->count();
        $belumDownload = max(0, $total - $sudahDownload);
        $belumFotoKtp = Doorprize::query()
            ->where(fn ($q) => $q->whereNull('foto_ktp')->orWhere('foto_ktp', ''))
            ->count();

        $totalTransaksi = 0;
        $totalRevenue = 0;

        Doorprize::query()
            ->select(['id', 'transactions'])
            ->orderBy('id')
            ->chunkById(200, function ($records) use (&$totalTransaksi, &$totalRevenue): void {
                foreach ($records as $record) {
                    $totalTransaksi += $record->total_nominal_transaksi;
                    $totalRevenue += $record->total_nominal_revenue;
                }
            });

        return [
            Stat::make('Total Pemenang', (string) $total)
                ->description('Total data doorprize')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Sudah Download TRING!', (string) $sudahDownload)
                ->description('Peserta yang sudah download aplikasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Belum Download', (string) $belumDownload)
                ->description('Peserta yang belum download aplikasi')
                ->descriptionIcon('heroicon-m-arrow-down-tray')
                ->color('warning'),

            Stat::make('Belum Upload Foto KTP', (string) $belumFotoKtp)
                ->description('Data yang belum melampirkan foto KTP')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color('danger'),

            Stat::make('Total Transaksi', Doorprize::formatRupiah($totalTransaksi))
                ->description('Akumulasi nominal transaksi')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Total Revenue', Doorprize::formatRupiah($totalRevenue))
                ->description('Akumulasi nominal revenue')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
