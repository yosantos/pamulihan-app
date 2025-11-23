<?php

namespace App\Filament\Resources\HeirCertificateResource\Pages;

use App\Filament\Resources\HeirCertificateResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditHeirCertificate extends EditRecord
{
    protected static string $resource = HeirCertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->color('info'),
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Heir Certificate Updated')
            ->body('The heir certificate has been updated successfully.');
    }
}
