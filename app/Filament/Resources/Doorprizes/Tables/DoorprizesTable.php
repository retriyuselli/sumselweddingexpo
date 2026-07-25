<?php

namespace App\Filament\Resources\Doorprizes\Tables;

use App\Models\Doorprize;
use App\Models\Expo;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DoorprizesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pemenang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->nik),
                ImageColumn::make('foto_ktp')
                    ->label('Foto KTP')
                    ->disk('local')
                    ->visibility('private')
                    ->circular(),
                TextColumn::make('partisipasi.expo.nama_expo')
                    ->label('Expo')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Doorprize $record) => self::expoLabelParts($record->partisipasi?->expo)),
                TextColumn::make('partisipasi.vendor.nama_vendor')
                    ->label('Tenant / Vendor')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('kodevoucher')
                    ->label('Kode Voucher')
                    ->searchable()
                    ->copyable()
                    ->badge()
                    ->color('success')
                    ->fontFamily('mono'),
                TextColumn::make('total_nom_trx')
                    ->label('Total Transaksi')
                    ->state(fn (Doorprize $record): string => Doorprize::formatRupiah($record->total_nominal_transaksi))
                    ->badge()
                    ->color('warning'),
                TextColumn::make('total_no_rev')
                    ->label('Total Revenue')
                    ->state(fn (Doorprize $record): string => Doorprize::formatRupiah($record->total_nominal_revenue))
                    ->badge()
                    ->color('success'),
                TextColumn::make('no_wa')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-m-phone')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('expo')
                    ->label('Filter Expo')
                    ->options(fn (): array => Expo::query()
                        ->orderByDesc('tanggal_mulai')
                        ->orderByDesc('id')
                        ->get()
                        ->mapWithKeys(fn (Expo $expo) => [$expo->id => self::expoFilterLabel($expo)])
                        ->all())
                    ->query(function (Builder $query, array $data) {
                        if (! empty($data['value'])) {
                            $query->whereHas('partisipasi', function ($query) use ($data) {
                                $query->where('expo_id', $data['value']);
                            });
                        }
                    })
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                Action::make('downloadForm')
                    ->label('Lihat / Cetak Form TRING')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn (Doorprize $record) => route('doorprizes.form-tring-pegadaian.pdf', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected static function expoFilterLabel(Expo $expo): string
    {
        $parts = self::expoDetailParts($expo);

        return $parts === []
            ? $expo->nama_expo.' [#'.$expo->id.']'
            : $expo->nama_expo.' ('.implode(' · ', $parts).')';
    }

    protected static function expoLabelParts(?Expo $expo): ?string
    {
        if (! $expo) {
            return null;
        }

        $parts = self::expoDetailParts($expo);

        return $parts === [] ? null : implode(' · ', $parts);
    }

    /**
     * @return list<string>
     */
    protected static function expoDetailParts(Expo $expo): array
    {
        $tanggal = null;
        if ($expo->tanggal_mulai) {
            $tanggal = $expo->tanggal_mulai->format('d M Y');
            if ($expo->tanggal_selesai && ! $expo->tanggal_mulai->equalTo($expo->tanggal_selesai)) {
                $tanggal .= ' – '.$expo->tanggal_selesai->format('d M Y');
            }
        }

        return array_values(array_filter([
            $expo->periode ? 'Periode '.$expo->periode : null,
            $tanggal,
        ]));
    }
}
