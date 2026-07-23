<?php

namespace App\Filament\Resources\Partisipasis\Tables;

use App\Models\Expo;
use App\Models\Partisipasi;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PartisipasisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->sortable()
                    ->description(fn ($record) => self::expoLabelParts($record->expo)),

                TextColumn::make('vendor.nama_vendor')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable()
                    ->description(fn ($record) => $record->categoryTenant?->category?->label()),

                TextColumn::make('tenantSpot.kode_booth')
                    ->label('No. Tenant')
                    ->sortable()
                    ->searchable()
                    ->placeholder('—')
                    ->description(fn ($record) => $record->tenantSpot
                        ? null
                        : ($record->blok_tenant ? 'Preferensi: '.$record->blok_tenant : null)),

                TextColumn::make('tanggal_booking')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Tanggal Booking'),

                TextColumn::make('harga_jual')
                    ->sortable()
                    ->label('Harga Jual')
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                IconColumn::make('is_barter')
                    ->boolean()
                    ->label('Barter')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),

                TextColumn::make('barter_nominal')
                    ->sortable()
                    ->label('Nominal Barter')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                BadgeColumn::make('status_pembayaran')
                    ->label('Status Pembayaran'),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif')
                    ->placeholder('Semua'),
                SelectFilter::make('expo_id')
                    ->relationship('expo', 'nama_expo')
                    ->getOptionLabelFromRecordUsing(fn (Expo $record) => self::expoFilterLabel($record))
                    ->searchable()
                    ->label('Expo'),
                SelectFilter::make('vendor_id')
                    ->relationship('vendor', 'nama_vendor')
                    ->label('Vendor'),
                SelectFilter::make('status_pembayaran')
                    ->options([
                        'Lunas' => 'Lunas',
                        'Belum Lunas' => 'Belum Lunas',
                        'DP' => 'DP (Down Payment)',
                        'Cicilan' => 'Cicilan',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('previewInvoice')
                        ->label('Preview Invoice')
                        ->icon('heroicon-o-eye')
                        ->color('gray')
                        ->url(fn (Partisipasi $record) => route('partisipasis.invoice', [
                            'partisipasi' => $record,
                            'download' => 0,
                        ]))
                        ->openUrlInNewTab(),
                    Action::make('downloadInvoice')
                        ->label('Unduh Invoice')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn (Partisipasi $record) => route('partisipasis.invoice', [
                            'partisipasi' => $record,
                            'download' => 1,
                        ]))
                        ->openUrlInNewTab(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    protected static function expoFilterLabel(Expo $expo): string
    {
        $parts = array_filter([
            $expo->periode ? 'Periode '.$expo->periode : null,
            $expo->tanggal_mulai?->format('d M Y'),
            $expo->lokasi ? Str::limit($expo->lokasi, 40) : null,
        ]);

        return $parts === []
            ? $expo->nama_expo.' [#'.$expo->id.']'
            : $expo->nama_expo.' ('.implode(' · ', $parts).')';
    }

    protected static function expoLabelParts(?Expo $expo): ?string
    {
        if (! $expo) {
            return null;
        }

        $parts = array_filter([
            $expo->periode,
            $expo->tanggal_mulai?->format('d M Y'),
            $expo->lokasi ? Str::limit($expo->lokasi, 40) : null,
        ]);

        return $parts === [] ? null : implode(' · ', $parts);
    }
}
