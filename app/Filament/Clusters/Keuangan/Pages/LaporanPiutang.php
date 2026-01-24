<?php

namespace App\Filament\Clusters\Keuangan\Pages;

use App\Filament\Clusters\Keuangan;
use App\Models\Partisipasi;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class LaporanPiutang extends Page implements HasTable
{
    use InteractsWithTable;

    protected string $view = 'filament.clusters.keuangan.pages.laporan-piutang';

    protected static ?string $cluster = Keuangan::class;
    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    
    protected static ?string $title = 'Laporan Piutang Tenant';

    protected static ?string $navigationLabel = 'Piutang Tenant';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Partisipasi::query()
                    ->where('status_pembayaran', '!=', 'Lunas')
                    ->where('sisa_pembayaran', '>', 0)
            )
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('vendor.nama_vendor')
                    ->label('Vendor / Tenant')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Partisipasi $record) => $record->categoryTenant->nama_kategori ?? '-'),

                TextColumn::make('blok_tenant')
                    ->label('Blok')
                    ->searchable(),
                
                TextColumn::make('harga_bersih')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('total_pembayaran')
                    ->label('Sudah Dibayar')
                    ->money('IDR')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('sisa_pembayaran')
                    ->label('Sisa Tagihan (Piutang)')
                    ->money('IDR')
                    ->color('danger')
                    ->weight('bold')
                    ->sortable(),
                
                TextColumn::make('status_pembayaran')
                    ->badge()
                    ->color('warning'),
            ])
            ->filters([
                SelectFilter::make('expo_id')
                    ->relationship('expo', 'nama_expo')
                    ->label('Filter per Expo'),
            ])
            ->defaultSort('sisa_pembayaran', 'desc')
            ->paginated([10, 25, 50, 100]);
    }
}
