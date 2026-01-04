<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Order;
use App\Models\Partisipasi;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Vendor', Vendor::count())
                ->description('Total vendor terdaftar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),

            Stat::make('Total Pengguna', User::count())
                ->description('Total pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Transaksi', Order::where('status', 'paid')->count())
                ->description('Total transaksi berhasil')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
                
            Stat::make('Peserta Expo', Partisipasi::count())
                ->description('Total peserta expo')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
        ];
    }
}
