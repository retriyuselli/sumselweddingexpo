<?php

namespace App\Filament\Resources\TenantSpots\Tables;

use App\Models\TenantSpot;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Notification;

class TenantSpotsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('kode_booth')
                    ->label('Kode Booth')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('blok')
                    ->label('Blok')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('nomor')
                    ->label('No.')
                    ->sortable(),

                TextColumn::make('section')
                    ->label('Seksi')
                    ->formatStateUsing(fn ($state) => $state ?? '—')
                    ->sortable(),

                TextColumn::make('baris')
                    ->label('Baris')
                    ->sortable(),

                TextColumn::make('kolom')
                    ->label('Kolom')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('blok')
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('expo_id')
                    ->relationship('expo', 'nama_expo')
                    ->label('Expo'),
                SelectFilter::make('blok')
                    ->options(fn () => TenantSpot::query()->distinct()->pluck('blok', 'blok')->sort()->toArray())
                    ->label('Blok'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
