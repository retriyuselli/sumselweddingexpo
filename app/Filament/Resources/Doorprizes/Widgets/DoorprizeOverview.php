<?php

namespace App\Filament\Resources\Doorprizes\Widgets;

use App\Models\Doorprize;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DoorprizeOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = Doorprize::count();

        $sudahDownload = Doorprize::where('sudah_download_tring', true)->count();

        $belumDownload = $total - $sudahDownload;

        $totalTransaksi = Doorprize::query()
            ->select('transactions')
            ->get()
            ->flatMap(fn (Doorprize $record) => $record->transactions ?? [])
            ->sum(fn ($item) => (int) str_replace(',', '', $item['nom_trx'] ?? 0));

        $totalRevenue = Doorprize::query()
            ->select('transactions')
            ->get()
            ->flatMap(fn (Doorprize $record) => $record->transactions ?? [])
            ->sum(fn ($item) => (int) str_replace(',', '', $item['no_rev'] ?? 0));

        $belumFotoKtp = Doorprize::whereNull('foto_ktp')
            ->orWhere('foto_ktp', '')
            ->count();

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

            Stat::make('Total Transaksi', '' . number_format($totalTransaksi, 0, ',', '.'))
                ->description('Akumulasi nominal transaksi')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Total Revenue', '' . number_format($totalRevenue, 0, ',', '.'))
                ->description('Akumulasi nominal revenue')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }
}
