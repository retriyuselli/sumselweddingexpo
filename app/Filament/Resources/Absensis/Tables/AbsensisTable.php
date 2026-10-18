<?php

namespace App\Filament\Resources\Absensis\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AbsensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('expo.nama_expo')
                    ->label('Expo')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->wrap(),
                TextColumn::make('no_tlp')
                    ->label('No. Telepon')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('created_at')
                    ->label('Waktu scan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('expo_id')
                    ->label('Expo')
                    ->relationship('expo', 'nama_expo')
                    ->searchable()
                    ->preload(),
                Filter::make('tanggal')
                    ->label('Tanggal hadir')
                    ->form([
                        DatePicker::make('tanggal')->label('Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['tanggal'] ?? null,
                            fn (Builder $query, string $date): Builder => $query->whereDate('tanggal', $date),
                        );
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
