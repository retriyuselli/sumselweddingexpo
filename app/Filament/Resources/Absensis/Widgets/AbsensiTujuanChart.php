<?php

namespace App\Filament\Resources\Absensis\Widgets;

use App\Enums\TujuanKunjungan;
use App\Models\Absensi;
use Filament\Widgets\ChartWidget;

class AbsensiTujuanChart extends ChartWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    protected ?string $heading = 'Tujuan kunjungan';

    protected ?string $description = 'Jumlah kehadiran pada setiap pilihan tujuan.';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 'full';

    protected ?string $pollingInterval = '30s';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $counts = Absensi::query()
            ->selectRaw('tujuan, COUNT(*) as total')
            ->groupBy('tujuan')
            ->pluck('total', 'tujuan');

        $labels = [];
        $data = [];

        foreach (TujuanKunjungan::options() as $value => $label) {
            $labels[] = $label;
            $data[] = (int) ($counts[$value] ?? 0);
        }

        $unfilled = (int) $counts
            ->filter(fn (mixed $total, mixed $tujuan): bool => blank($tujuan))
            ->sum();
        if ($unfilled > 0) {
            $labels[] = 'Belum diisi';
            $data[] = $unfilled;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kehadiran',
                    'data' => $data,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}
