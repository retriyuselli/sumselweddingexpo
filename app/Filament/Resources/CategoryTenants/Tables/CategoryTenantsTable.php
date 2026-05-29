<?php

namespace App\Filament\Resources\CategoryTenants\Tables;

use App\Enums\CategoryTier;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CategoryTenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn (?CategoryTier $state) => $state?->label() ?? '-'),

                TextColumn::make('harga_jual')
                    ->sortable()
                    ->label('Harga Jual')
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('harga_modal')
                    ->sortable()
                    ->label('Harga Modal')
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => is_null($state) ? '-' : 'Rp '.number_format((int) $state, 0, ',', '.')),

                TextColumn::make('jumlah_unit')
                    ->label('Unit')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => fn ($state) => (bool) $state,
                        'danger' => fn ($state) => ! (bool) $state,
                    ])
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Tidak Aktif'),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('expo_id')
                    ->relationship('expo', 'nama_expo')
                    ->label('Expo'),
                SelectFilter::make('category')
                    ->options(CategoryTier::options())
                    ->label('Kategori'),
                SelectFilter::make('status')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Tidak Aktif',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                ReplicateAction::make()
                    ->excludeAttributes(['created_at', 'updated_at', 'deleted_at'])
                    ->beforeReplicaSaved(function ($replica) {
                        $replica->status = 'Tidak Aktif';
                    })
                    ->successNotificationTitle('Data berhasil diduplikasi'),
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
