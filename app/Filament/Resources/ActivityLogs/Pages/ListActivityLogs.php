<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Spatie\Activitylog\Models\Activity;

class ListActivityLogs extends ListRecords
{
    protected static string $resource = ActivityLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_old_logs')
                ->label('Hapus Log Lama (>30 hari)')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Hapus Log Lama')
                ->modalDescription('Apakah Anda yakin ingin menghapus semua log yang lebih dari 30 hari? Tindakan ini tidak dapat dibatalkan.')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->action(function () {
                    $deleted = Activity::where('created_at', '<', now()->subDays(30))->delete();
                    \Filament\Notifications\Notification::make()
                        ->title("{$deleted} log lama berhasil dihapus")
                        ->success()
                        ->send();
                })
                ->visible(fn() => auth()->user()?->hasRole('super_admin')),
        ];
    }
}
