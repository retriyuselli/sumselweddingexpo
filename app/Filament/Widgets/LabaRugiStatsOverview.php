<?php

namespace App\Filament\Widgets;

use App\Models\Expo;
use App\Models\DataPembayaran;
use App\Models\Sponsor;
use App\Models\Pengeluaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class LabaRugiStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $expos = Expo::all();
        
        $totalPemasukanGlobal = 0;
        $totalPengeluaranGlobal = 0;

        foreach ($expos as $expo) {
            // Pemasukan dari Partisipasi (DataPembayaran)
            $pemasukanPartisipasi = DataPembayaran::whereHas('partisipasi', function ($query) use ($expo) {
                $query->where('expo_id', $expo->id);
            })->sum('nominal');

            // Pemasukan dari Sponsor
            $pemasukanSponsor = Sponsor::where('expo_id', $expo->id)->sum('nominal');

            // Total Pemasukan
            $totalPemasukanGlobal += ($pemasukanPartisipasi + $pemasukanSponsor);

            // Pengeluaran
            $totalPengeluaranGlobal += Pengeluaran::where('expo_id', $expo->id)->sum('nominal');
        }

        $totalLabaRugiGlobal = $totalPemasukanGlobal - $totalPengeluaranGlobal;

        return [
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalPemasukanGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedArrowTrendingUp)
                ->color('success'),
            
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalPengeluaranGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedArrowTrendingDown)
                ->color('danger'),
            
            Stat::make('Laba Bersih', 'Rp ' . number_format($totalLabaRugiGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedBanknotes)
                ->color($totalLabaRugiGlobal >= 0 ? 'primary' : 'danger'),
        ];
    }
}
