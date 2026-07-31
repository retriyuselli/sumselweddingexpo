<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users')
                ->icon('heroicon-o-user-group')
                ->badge(fn () => \App\Models\User::count()),

            'verified' => Tab::make('Verified')
                ->icon('heroicon-o-check-badge')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('email_verified_at'))
                ->badge(fn () => \App\Models\User::whereNotNull('email_verified_at')->count())
                ->badgeColor('success'),

            'unverified' => Tab::make('Unverified')
                ->icon('heroicon-o-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('email_verified_at'))
                ->badge(fn () => \App\Models\User::whereNull('email_verified_at')->count())
                ->badgeColor('danger'),

            'admins' => Tab::make('Admins')
                ->icon('heroicon-o-shield-check')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('roles', fn ($q) => $q->where('name', 'super_admin')))
                ->badge(fn () => \App\Models\User::whereHas('roles', fn ($q) => $q->where('name', 'super_admin'))->count())
                ->badgeColor('warning'),
        ];
    }
}
