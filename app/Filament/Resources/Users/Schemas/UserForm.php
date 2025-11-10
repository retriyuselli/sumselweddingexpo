<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Personal Information')
                    ->description('Basic user information and contact details')
                    ->icon('heroicon-o-user')
                    ->columns(1)
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter full name')
                            ->autocomplete('name')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => 
                                $set('name', ucwords(strtolower($state)))
                            ),
                        
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('user@example.com')
                            ->autocomplete('email')
                            ->suffixIcon('heroicon-m-envelope')
                            ->live(onBlur: true),
                        
                        FileUpload::make('avatar_url')
                            ->label('Profile Photo')
                            ->avatar()
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048)
                            ->helperText('Upload a profile photo (max 2MB, stored securely)')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                    ]),
                
                Section::make('Security & Access')
                    ->description('Password and role management')
                    ->icon('heroicon-o-shield-check')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn ($state) => !empty($state) ? Hash::make($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->rule(Password::default())
                            ->placeholder('Enter password')
                            ->helperText(fn (string $context) => 
                                $context === 'create' 
                                    ? 'Minimum 8 characters (required)'
                                    : 'Leave empty to keep current password'
                            )
                            ->autocomplete('new-password')
                            ->suffixIcon('heroicon-m-key')
                            ->validationMessages([
                                'required' => 'Password is required for new users.',
                            ]),
                        
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->requiredWith('password')
                            ->same('password')
                            ->placeholder('Confirm password')
                            ->autocomplete('new-password')
                            ->suffixIcon('heroicon-m-key')
                            ->validationMessages([
                                'same' => 'Passwords must match.',
                            ]),
                        
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->columnSpanFull()
                            ->helperText('Assign roles to control user permissions (Spatie Permission)')
                            ->placeholder('Select one or more roles')
                            ->native(false)
                            ->suffixIcon('heroicon-m-shield-check'),
                    ]),
                
                Section::make('Account Status')
                    ->description('Email verification and account status')
                    ->icon('heroicon-o-envelope-open')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->displayFormat('d M Y H:i')
                            ->native(false)
                            ->seconds(false)
                            ->helperText('Leave empty if email is not verified')
                            ->suffixIcon('heroicon-m-calendar-days'),
                        
                        Toggle::make('is_verified')
                            ->label('Mark as Verified')
                            ->inline(false)
                            ->visible(fn ($context) => $context === 'create')
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('email_verified_at', now());
                                } else {
                                    $set('email_verified_at', null);
                                }
                            })
                            ->reactive()
                            ->helperText('Enable to automatically verify email on creation'),
                    ]),
            ]);
    }
}
