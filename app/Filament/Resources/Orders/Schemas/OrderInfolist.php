<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Summary')
                    ->icon('heroicon-o-shopping-cart')
                    ->columns(3)
                    ->schema([
                        TextEntry::make('code')
                            ->label('Order ID')
                            ->weight('bold'),

                        TextEntry::make('status')
                            ->label('Status')
                            ->badge(),

                        TextEntry::make('amount_total')
                            ->label('Total')
                            ->formatStateUsing(fn ($state) => 'Rp '.number_format((float) $state, 0, ',', '.')),

                        TextEntry::make('customer.name')
                            ->label('Customer')
                            ->columnSpan(2),

                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y H:i'),
                    ]),

                Section::make('Billing')
                    ->icon('heroicon-o-user')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('billing_first_name')
                            ->label('First Name'),
                        TextEntry::make('billing_last_name')
                            ->label('Last Name'),
                        TextEntry::make('billing_phone')
                            ->label('Phone'),
                        TextEntry::make('billing_email')
                            ->label('Email'),
                        TextEntry::make('billing_street')
                            ->label('Street')
                            ->columnSpanFull(),
                        TextEntry::make('billing_city')
                            ->label('City'),
                        TextEntry::make('billing_province')
                            ->label('Province'),
                        TextEntry::make('billing_postcode')
                            ->label('Postcode'),
                    ]),
            ]);
    }
}