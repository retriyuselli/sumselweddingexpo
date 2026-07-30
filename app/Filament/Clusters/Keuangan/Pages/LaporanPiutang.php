<?php

namespace App\Filament\Clusters\Keuangan\Pages;

use App\Filament\Resources\Partisipasis\PartisipasiResource;
use App\Filament\Widgets\LaporanPiutangStatsOverview;
use App\Models\Expo;
use App\Models\Partisipasi;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class LaporanPiutang extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.clusters.keuangan.pages.laporan-piutang';

    protected static string|UnitEnum|null $navigationGroup = 'Keuangan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $title = 'Laporan Piutang Tenant';

    protected static ?string $navigationLabel = 'Piutang Tenant';

    protected static ?int $navigationSort = 2;

    protected function getHeaderWidgets(): array
    {
        return [
            LaporanPiutangStatsOverview::class,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Partisipasi::query()
                    ->where('sisa_pembayaran', '>', 0)
                    ->with(['expo', 'vendor', 'categoryTenant', 'tenantSpot'])
            )
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Partisipasi $record): ?string => $record->expo?->labelDetails()),

                TextColumn::make('vendor.nama_vendor')
                    ->label('Vendor / Tenant')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Partisipasi $record): string => $record->categoryTenant?->nama_kategori ?? '—'),

                TextColumn::make('tenantSpot.kode_booth')
                    ->label('Blok')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('harga_bersih')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable()
                    ->alignRight(),

                TextColumn::make('total_pembayaran')
                    ->label('Sudah Dibayar')
                    ->money('IDR')
                    ->color('success')
                    ->sortable()
                    ->alignRight(),

                TextColumn::make('sisa_pembayaran')
                    ->label('Sisa Tagihan (Piutang)')
                    ->money('IDR')
                    ->color('danger')
                    ->weight('bold')
                    ->sortable()
                    ->alignRight(),

                TextColumn::make('is_barter')
                    ->label('Barter')
                    ->badge()
                    ->formatStateUsing(fn (?bool $state): string => $state ? 'Ya' : 'Tidak')
                    ->color(fn (?bool $state): string => $state ? 'info' : 'gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status_pembayaran')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Lunas' => 'success',
                        'DP', 'DP (Down Payment)', 'Cicilan' => 'warning',
                        default => 'danger',
                    }),
            ])
            ->filters([
                SelectFilter::make('expo_id')
                    ->label('Filter per Expo')
                    ->options(fn (): array => Expo::query()
                        ->orderByDesc('tanggal_mulai')
                        ->orderByDesc('id')
                        ->get()
                        ->mapWithKeys(fn (Expo $expo) => [$expo->id => $expo->labelForSelect()])
                        ->all())
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('edit')
                        ->label('Edit Partisipasi')
                        ->icon('heroicon-o-pencil-square')
                        ->url(fn (Partisipasi $record): string => PartisipasiResource::getUrl('edit', ['record' => $record])),
                ]),
            ])
            ->defaultSort('sisa_pembayaran', 'desc')
            ->paginated([10, 25, 50, 100])
            ->emptyStateHeading('Tidak ada piutang')
            ->emptyStateDescription('Semua partisipasi sudah lunas, atau belum ada data tagihan tertunggak.');
    }
}
