<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Profile')
                    ->icon('heroicon-o-user-circle')
                    ->columns(3)
                    ->schema([
                        ImageEntry::make('avatar_url')
                            ->label('Profile Photo')
                            ->circular()
                            ->getStateUsing(fn ($record) => $record->getFilamentAvatarUrl())
                            ->defaultImageUrl(fn ($record) => 
                                'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF&size=200'
                            )
                            ->columnSpan(1),
                        
                        TextEntry::make('name')
                            ->label('Full Name')
                            ->size('lg')
                            ->weight('bold')
                            ->icon('heroicon-m-user')
                            ->columnSpan(2),
                        
                        TextEntry::make('email')
                            ->label('Email Address')
                            ->copyable()
                            ->icon('heroicon-m-envelope')
                            ->columnSpan(2),
                    ]),
                
                Section::make('Roles & Permissions')
                    ->icon('heroicon-o-shield-check')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('roles.name')
                            ->label('Assigned Roles')
                            ->badge()
                            ->colors([
                                'danger' => 'super_admin',
                                'warning' => 'panel_user',
                                'success' => fn ($state): bool => !in_array($state, ['super_admin', 'panel_user']),
                            ])
                            ->separator(',')
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Account Information')
                    ->icon('heroicon-o-information-circle')
                    ->columns(3)
                    ->schema([
                        IconEntry::make('email_verified_at')
                            ->label('Email Verified')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        
                        TextEntry::make('email_verified_at')
                            ->label('Verified At')
                            ->dateTime('d M Y H:i')
                            ->placeholder('Not verified')
                            ->icon('heroicon-m-calendar'),
                        
                        TextEntry::make('created_at')
                            ->label('Member Since')
                            ->dateTime('d M Y')
                            ->icon('heroicon-m-calendar-days'),
                        
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d M Y H:i')
                            ->icon('heroicon-m-clock')
                            ->since()
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
