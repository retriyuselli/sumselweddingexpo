<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('amount_total')
                    ->label('Total')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.')),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['paid','dp_paid']),
                        'warning' => 'pending',
                        'danger' => fn ($state) => in_array($state, ['failed','cancelled','expire']),
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ((string) $state) {
                            'paid' => 'Paid',
                            'dp_paid' => 'DP Paid',
                            'pending' => 'Pending',
                            'failed' => 'Failed',
                            'cancelled' => 'Cancelled',
                            'expire' => 'Expired',
                            default => ucfirst((string) $state),
                        };
                    }),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                        'expire' => 'Expired',
                    ])
                    ->label('Status'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}