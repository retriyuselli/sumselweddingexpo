<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Order;
use App\Models\Partisipasi;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $expoId = $this->filters['expo_id'] ?? null;

        $vendorQuery = Vendor::query();
        $userQuery = User::query();
        $orderQuery = Order::query();
        $partisipasiQuery = Partisipasi::query();

        if ($expoId) {
            $partisipasiQuery->where('expo_id', $expoId);
            $vendorQuery->whereHas('partisipasis', function ($q) use ($expoId) {
                $q->where('expo_id', $expoId);
            });
        }

        if ($startDate) {
            $vendorQuery->whereDate('created_at', '>=', $startDate);
            $userQuery->whereDate('created_at', '>=', $startDate);
            $orderQuery->whereDate('created_at', '>=', $startDate);
            $partisipasiQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $vendorQuery->whereDate('created_at', '<=', $endDate);
            $userQuery->whereDate('created_at', '<=', $endDate);
            $orderQuery->whereDate('created_at', '<=', $endDate);
            $partisipasiQuery->whereDate('created_at', '<=', $endDate);
        }

        return [
            Stat::make('Total Vendor', $vendorQuery->count())
                ->description('Total vendor terdaftar')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary'),

            Stat::make('Total Pengguna', $userQuery->count())
                ->description('Total pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Transaksi', $orderQuery->where('status', 'paid')->count())
                ->description('Total transaksi berhasil')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
                
            Stat::make('Peserta Expo', $partisipasiQuery->count())
                ->description('Total peserta expo')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
        ];
    }
}
