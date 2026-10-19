<?php

namespace App\Filament\Resources\Absensis\Widgets;

use App\Enums\TujuanKunjungan;
use App\Models\Absensi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AbsensiOverview extends StatsOverviewWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected ?string $pollingInterval = '30s';

    protected int|array|null $columns = [
        'default' => 1,
        'sm' => 2,
        'xl' => 4,
    ];

    protected function getStats(): array
    {
        $today = now()->timezone('Asia/Jakarta')->toDateString();
        $start = Carbon::parse($today)->subDays(6)->toDateString();

        $total = Absensi::query()->count();
        $todayCount = Absensi::query()->whereDate('tanggal', $today)->count();
        $uniqueVisitors = Absensi::query()->distinct('email')->count('email');

        $daily = Absensi::query()
            ->whereDate('tanggal', '>=', $start)
            ->selectRaw('tanggal, COUNT(*) as total')
            ->groupBy('tanggal')
            ->get()
            ->mapWithKeys(fn (Absensi $row): array => [
                $row->tanggal->toDateString() => (int) $row->total,
            ]);

        $trend = collect(range(6, 0))
            ->map(function (int $daysAgo) use ($today, $daily): int {
                $day = Carbon::parse($today)->subDays($daysAgo)->toDateString();

                return (int) ($daily[$day] ?? 0);
            })
            ->all();

        $top = Absensi::query()
            ->selectRaw('tujuan, COUNT(*) as total')
            ->whereNotNull('tujuan')
            ->groupBy('tujuan')
            ->orderByDesc('total')
            ->first();

        $topCount = 0;
        $topDescription = 'Belum ada tujuan yang tercatat';

        if ($top?->tujuan) {
            $topCount = (int) $top->total;
            $topDescription = TujuanKunjungan::tryFrom((string) $top->tujuan)?->label() ?? (string) $top->tujuan;
        }

        return [
            Stat::make('Hadir hari ini', number_format($todayCount, 0, ',', '.'))
                ->description('Catatan kehadiran tanggal ini')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart($trend)
                ->color('primary'),

            Stat::make('Total kehadiran', number_format($total, 0, ',', '.'))
                ->description('Seluruh catatan absensi')
                ->descriptionIcon('heroicon-m-qr-code')
                ->color('success'),

            Stat::make('Pengunjung unik', number_format($uniqueVisitors, 0, ',', '.'))
                ->description('Dihitung dari email yang berbeda')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Tujuan terbanyak', number_format($topCount, 0, ',', '.'))
                ->description($topDescription)
                ->descriptionIcon('heroicon-m-flag')
                ->color('warning'),
        ];
    }
}
