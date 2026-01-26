<?php

namespace App\Filament\Clusters\Keuangan\Pages;

use App\Filament\Clusters\Keuangan;
use App\Filament\Widgets\LabaRugiStatsOverview;
use App\Models\Expo;
use App\Models\DataPembayaran;
use App\Models\Sponsor;
use App\Models\Partisipasi;
use App\Models\Pengeluaran;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class LabaRugiReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.clusters.keuangan.pages.laba-rugi-report';

    protected static ?string $cluster = Keuangan::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;
    
    protected static ?string $title = 'Laporan Laba Rugi per Expo';

    protected static ?string $navigationLabel = 'Laba Rugi';

    protected function getHeaderWidgets(): array
    {
        return [
            LabaRugiStatsOverview::class,
        ];
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
                        $total = DataPembayaran::whereHas('partisipasi', function ($query) use ($record) {
                            $query->where('expo_id', $record->id);
                        })->sum('nominal');
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->alignRight(),

                TextColumn::make('pemasukan_sponsor')
                    ->label('Sponsor')
                    ->state(function (Expo $record): string {
                        $total = Sponsor::where('expo_id', $record->id)->sum('nominal');
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->alignRight(),

                TextColumn::make('total_pemasukan')
                    ->label('Total Pemasukan')
                    ->state(function (Expo $record): string {
                        $partisipasi = DataPembayaran::whereHas('partisipasi', function ($query) use ($record) {
                            $query->where('expo_id', $record->id);
                        })->sum('nominal');
                        $sponsor = Sponsor::where('expo_id', $record->id)->sum('nominal');
                        return 'Rp ' . number_format($partisipasi + $sponsor, 0, ',', '.');
                    })
                    ->color('success')
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('total_pengeluaran')
                    ->label('Total Pengeluaran')
                    ->state(function (Expo $record): string {
                        $total = Pengeluaran::where('expo_id', $record->id)->sum('nominal');
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->color('danger')
                    ->weight('bold')
                    ->alignRight(),

                TextColumn::make('piutang')
                    ->label('Piutang')
                    ->state(function (Expo $record): string {
                        $total = Partisipasi::where('expo_id', $record->id)
                            ->where('status_pembayaran', '!=', 'Lunas')
                            ->sum('sisa_pembayaran');
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->color('warning')
                    ->alignRight(),

                TextColumn::make('barter')
                    ->label('Barter')
                    ->state(function (Expo $record): string {
                        $total = Partisipasi::where('expo_id', $record->id)
                            ->where('is_barter', true)
                            ->sum('barter_nominal');
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    })
                    ->color('info')
                    ->alignRight(),
                
                TextColumn::make('status_laba_rugi')
                    ->label('Status')
                    ->badge()
                    ->state(function (Expo $record): string {
                        $partisipasi = DataPembayaran::whereHas('partisipasi', function ($query) use ($record) {
                            $query->where('expo_id', $record->id);
                        })->sum('nominal');
                        $sponsor = Sponsor::where('expo_id', $record->id)->sum('nominal');
                        $pemasukan = $partisipasi + $sponsor;
                        
                        $pengeluaran = Pengeluaran::where('expo_id', $record->id)->sum('nominal');
                        $labaRugi = $pemasukan - $pengeluaran;

                        if ($labaRugi > 0) return 'Untung';
                        if ($labaRugi < 0) return 'Rugi';
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
                        $partisipasi = DataPembayaran::whereHas('partisipasi', function ($query) use ($record) {
                            $query->where('expo_id', $record->id);
                        })->sum('nominal');
                        $sponsor = Sponsor::where('expo_id', $record->id)->sum('nominal');
                        $pemasukan = $partisipasi + $sponsor;
                        
                        $pengeluaran = Pengeluaran::where('expo_id', $record->id)->sum('nominal');
                        $labaRugi = $pemasukan - $pengeluaran;

                        return 'Rp ' . number_format($labaRugi, 0, ',', '.');
                    })
                    ->color(function (Expo $record): string {
                        $partisipasi = DataPembayaran::whereHas('partisipasi', function ($query) use ($record) {
                            $query->where('expo_id', $record->id);
                        })->sum('nominal');
                        $sponsor = Sponsor::where('expo_id', $record->id)->sum('nominal');
                        $pemasukan = $partisipasi + $sponsor;
                        
                        $pengeluaran = Pengeluaran::where('expo_id', $record->id)->sum('nominal');
                        $labaRugi = $pemasukan - $pengeluaran;
                        
                        return $labaRugi >= 0 ? 'success' : 'danger';
                    })
                    ->weight('bold')
                    ->alignRight(),
            ])
            ->paginated(false);
    }
}
