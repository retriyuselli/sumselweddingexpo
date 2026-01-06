<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label('Avatar')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&color=7F9CF5&background=EBF4FF&size=200'
                    ),

                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->description(fn ($record) => $record->email),

                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-m-envelope')
                    ->toggleable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->colors([
                        'danger' => 'super_admin',
                        'warning' => 'panel_user',
                        'success' => fn ($state): bool => ! in_array($state, ['super_admin', 'panel_user']),
                    ])
                    ->separator(',')
                    ->searchable(),

                TextColumn::make('vendor.nama_vendor')
                    ->label('Vendor')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ?? '—'),

                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->formatStateUsing(fn ($state) => $state ?? '—'),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->alignCenter()
                    ->tooltip(fn ($record) => $record->email_verified_at
                        ? 'Verified on '.$record->email_verified_at->format('d M Y')
                        : 'Not verified'
                    ),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable()
                    ->description(fn ($record) => $record->created_at->diffForHumans()),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->label('Filter by Role'),

                TernaryFilter::make('email_verified_at')
                    ->label('Email Verified')
                    ->placeholder('All users')
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('email_verified_at'),
                        false: fn ($query) => $query->whereNull('email_verified_at'),
                    ),

                TernaryFilter::make('has_vendor')
                    ->label('Vendor Terdaftar')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya yang punya vendor')
                    ->falseLabel('Tanpa vendor')
                    ->queries(
                        true: fn ($query) => $query->whereHas('vendor'),
                        false: fn ($query) => $query->whereDoesntHave('vendor'),
                    ),

                TernaryFilter::make('has_avatar')
                    ->label('Memiliki Avatar')
                    ->placeholder('Semua')
                    ->trueLabel('Hanya yang punya avatar')
                    ->falseLabel('Tanpa avatar')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('avatar_url'),
                        false: fn ($query) => $query->whereNull('avatar_url'),
                    ),

                Filter::make('created_between')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->native(false),
                        DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->native(false),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    })
                    ->label('Tanggal Daftar'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No users yet')
            ->emptyStateDescription('Start by creating a new user.')
            ->emptyStateIcon('heroicon-o-user-group');
    }
}
