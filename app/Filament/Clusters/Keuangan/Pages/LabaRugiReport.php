<?php

namespace App\Filament\Clusters\Keuangan\Pages;

use App\Filament\Widgets\LabaRugiStatsOverview;
use App\Models\Expo;
use App\Services\LabaRugiAggregator;
use BackedEnum;
use Filament\Actions\Action;
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
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Expo::query())
            ->columns([
                TextColumn::make('nama_expo')
                    ->label('Nama Expo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('pemasukan_partisipasi')
                    ->label('Partisipasi')
                    ->state(function (Expo $record): string {
                        $total = (float) ($this->financeTotals()['partisipasi'][$record->id] ?? 0);

                        return $this->money($total);
                    })
                    ->alignRight(),

                TextColumn::make('pemasukan_sponsor')
                    ->label('Sponsor')
                    ->state(function (Expo $record): string {
                        $total = (float) ($this->financeTotals()['sponsor'][$record->id] ?? 0);

                        return $this->money($total);
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
                        $total = (float) ($this->financeTotals()['pengeluaran'][$record->id] ?? 0);

                        return $this->money($total);
                    })
                    ->color('danger')
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('piutang')
                    ->label('Piutang')
                    ->state(function (Expo $record): string {
                        $total = (float) ($this->financeTotals()['piutang'][$record->id] ?? 0);

                        return $this->money($total);
                    })
                    ->color('warning')
                    ->alignRight(),

                TextColumn::make('barter')
                    ->label('Barter')
                    ->state(function (Expo $record): string {
                        $total = (float) ($this->financeTotals()['barter'][$record->id] ?? 0);

                        return $this->money($total);
                    })
                    ->color('info')
                    ->alignRight(),

                TextColumn::make('status_laba_rugi')
                    ->label('Status')
                    ->badge()
                    ->state(function (Expo $record): string {
                        $totals = $this->financeTotals();
                        $pemasukan = (float) ($totals['partisipasi'][$record->id] ?? 0)
                            + (float) ($totals['sponsor'][$record->id] ?? 0);
                        $pengeluaran = (float) ($totals['pengeluaran'][$record->id] ?? 0);
                        $labaRugi = $pemasukan - $pengeluaran;

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
                        'Impas' => 'gray',
                    })
                    ->alignCenter(),

                TextColumn::make('laba_rugi')
                    ->label('Laba / Rugi')
                    ->state(function (Expo $record): string {
                        $totals = $this->financeTotals();
                        $pemasukan = (float) ($totals['partisipasi'][$record->id] ?? 0)
                            + (float) ($totals['sponsor'][$record->id] ?? 0);
                        $pengeluaran = (float) ($totals['pengeluaran'][$record->id] ?? 0);

                        return $this->money($pemasukan - $pengeluaran);
                    })
                    ->color(fn (string $state): string => str_contains($state, '-') ? 'danger' : 'success')
                    ->weight('bold')
                    ->alignRight(),
            ])
            ->actions([
                Action::make('download')
                    ->label('Download Laporan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Expo $record) => route('laporan.laba-rugi.stream', $record))
                    ->openUrlInNewTab(),
            ])
            ->paginated(false);
    }
}
