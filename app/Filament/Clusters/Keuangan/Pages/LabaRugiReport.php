<?php

namespace App\Filament\Clusters\Keuangan\Pages;

use App\Filament\Widgets\LabaRugiStatsOverview;
use App\Models\Expo;
use App\Services\LabaRugiAggregator;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class LabaRugiReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.clusters.keuangan.pages.laba-rugi-report';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static ?string $title = 'Laporan Laba Rugi per Expo';

    protected static ?string $navigationLabel = 'Laba Rugi';

    protected static ?int $navigationSort = 1;

    /** @var array<string, \Illuminate\Support\Collection<int, float>>|null */
    protected ?array $financeTotals = null;

    protected function getHeaderWidgets(): array
    {
        return [
            LabaRugiStatsOverview::class,
        ];
    }

    protected function financeTotals(): array
    {
        return $this->financeTotals ??= app(LabaRugiAggregator::class)->totalsByExpo();
    }

    protected function money(float $amount): string
    {
        return LabaRugiAggregator::formatRupiah($amount);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Expo::query()->orderByDesc('tanggal_mulai')->orderByDesc('id'))
            ->defaultSort('tanggal_mulai', 'desc')
            ->columns([
                TextColumn::make('nama_expo')
                    ->label('Nama Expo')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Expo $record): ?string => $record->labelDetails()),

                TextColumn::make('pemasukan_partisipasi')
                    ->label('Partisipasi')
                    ->state(function (Expo $record): string {
                        return $this->money((float) ($this->financeTotals()['partisipasi'][$record->id] ?? 0));
                    })
                    ->alignRight(),

                TextColumn::make('pemasukan_sponsor')
                    ->label('Sponsor')
                    ->state(function (Expo $record): string {
                        return $this->money((float) ($this->financeTotals()['sponsor'][$record->id] ?? 0));
                    })
                    ->alignRight(),

                TextColumn::make('total_pemasukan')
                    ->label('Total Pemasukan')
                    ->state(function (Expo $record): string {
                        $totals = $this->financeTotals();
                        $total = (float) ($totals['partisipasi'][$record->id] ?? 0)
                            + (float) ($totals['sponsor'][$record->id] ?? 0);

                        return $this->money($total);
                    })
                    ->color('success')
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('total_pengeluaran')
                    ->label('Total Pengeluaran')
                    ->state(function (Expo $record): string {
                        return $this->money((float) ($this->financeTotals()['pengeluaran'][$record->id] ?? 0));
                    })
                    ->color('danger')
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('piutang')
                    ->label('Piutang')
                    ->state(function (Expo $record): string {
                        return $this->money((float) ($this->financeTotals()['piutang'][$record->id] ?? 0));
                    })
                    ->color('warning')
                    ->alignRight(),

                TextColumn::make('barter')
                    ->label('Barter')
                    ->state(function (Expo $record): string {
                        return $this->money((float) ($this->financeTotals()['barter'][$record->id] ?? 0));
                    })
                    ->color('info')
                    ->alignRight(),

                TextColumn::make('status_laba_rugi')
                    ->label('Status')
                    ->badge()
                    ->state(function (Expo $record): string {
                        $labaRugi = $this->labaRugiFor($record);

                        if ($labaRugi > 0) {
                            return 'Untung';
                        }
                        if ($labaRugi < 0) {
                            return 'Rugi';
                        }

                        return 'Impas';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Untung' => 'success',
                        'Rugi' => 'danger',
                        default => 'gray',
                    })
                    ->alignCenter(),

                TextColumn::make('laba_rugi')
                    ->label('Laba / Rugi')
                    ->state(fn (Expo $record): string => $this->money($this->labaRugiFor($record)))
                    ->color(fn (Expo $record): string => $this->labaRugiFor($record) < 0 ? 'danger' : 'success')
                    ->weight('bold')
                    ->alignRight(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('preview')
                        ->label('Preview Laporan')
                        ->icon('heroicon-o-eye')
                        ->color('gray')
                        ->url(fn (Expo $record) => route('laporan.laba-rugi.stream', $record))
                        ->openUrlInNewTab(),
                    Action::make('download')
                        ->label('Download PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn (Expo $record) => route('laporan.laba-rugi.download', $record))
                        ->openUrlInNewTab(),
                ]),
            ])
            ->paginated(false)
            ->emptyStateHeading('Belum ada data expo')
            ->emptyStateDescription('Tambahkan expo terlebih dahulu untuk melihat laporan laba rugi.');
    }

    protected function labaRugiFor(Expo $record): float
    {
        $totals = $this->financeTotals();
        $pemasukan = (float) ($totals['partisipasi'][$record->id] ?? 0)
            + (float) ($totals['sponsor'][$record->id] ?? 0);
        $pengeluaran = (float) ($totals['pengeluaran'][$record->id] ?? 0);

        return $pemasukan - $pengeluaran;
    }
}
