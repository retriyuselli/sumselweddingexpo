<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('verify_email')
                ->label('Verify Email')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn ($record) => $record->email_verified_at === null)
                ->requiresConfirmation()
                ->modalHeading('Verify User Email')
                ->modalDescription('Are you sure you want to verify this user\'s email address?')
                ->modalSubmitActionLabel('Yes, verify')
                ->action(function ($record) {
                    $record->email_verified_at = now();
                    $record->save();

                    Notification::make()
                        ->title('Email verified successfully')
                        ->body('The user can now access all features.')
                        ->success()
                        ->send();

                    // Refresh the page to show updated data
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $record]));
                }),

            Action::make('unverify_email')
                ->label('Unverify Email')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn ($record) => $record->email_verified_at !== null)
                ->requiresConfirmation()
                ->modalHeading('Unverify User Email')
                ->modalDescription('Are you sure you want to unverify this user\'s email address?')
                ->modalSubmitActionLabel('Yes, unverify')
                ->action(function ($record) {
                    $record->email_verified_at = null;
                    $record->save();

                    Notification::make()
                        ->title('Email unverified')
                        ->body('The user email verification has been removed.')
                        ->warning()
                        ->send();

                    // Refresh the page to show updated data
                    $this->redirect($this->getResource()::getUrl('view', ['record' => $record]));
                }),

            EditAction::make()
                ->icon('heroicon-o-pencil'),

            DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
