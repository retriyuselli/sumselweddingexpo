<?php

namespace App\Filament\Widgets;

use App\Models\Expo;
use App\Models\DataPembayaran;
use App\Models\Sponsor;
use App\Models\Partisipasi;
use App\Models\Pengeluaran;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Support\Icons\Heroicon;

class LabaRugiStatsOverview extends BaseWidget
{
    public ?string $heading = 'Data Laba Rugi';
    
    protected function getStats(): array
    {
        $expos = Expo::all();
        
        $totalPemasukanGlobal = 0;
        $totalPengeluaranGlobal = 0;
        $totalPiutangGlobal = 0;

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

            // Piutang
            $totalPiutangGlobal += Partisipasi::where('expo_id', $expo->id)
                ->where('status_pembayaran', '!=', 'Lunas')
                ->sum('sisa_pembayaran');
        }

        $totalLabaRugiGlobal = $totalPemasukanGlobal - $totalPengeluaranGlobal;

        return [
            Stat::make('Total Pemasukan', '' . number_format($totalPemasukanGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedArrowTrendingUp)
                ->color('success'),
            
            Stat::make('Total Pengeluaran', '' . number_format($totalPengeluaranGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedArrowTrendingDown)
                ->color('danger'),
            
            Stat::make('Total Piutang', '' . number_format($totalPiutangGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedExclamationCircle)
                ->description('Potensi pemasukan tertunda')
                ->color('warning'),

            Stat::make('Laba Bersih', '' . number_format($totalLabaRugiGlobal, 0, ',', '.'))
                ->icon(Heroicon::OutlinedBanknotes)
                ->description('Berdasarkan uang masuk (Cash Basis)')
                ->color($totalLabaRugiGlobal >= 0 ? 'primary' : 'danger'),
        ];
    }
}
