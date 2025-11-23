<?php

namespace App\Filament\Resources\HeirCertificateResource\Pages;

use App\Filament\Resources\HeirCertificateResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateHeirCertificate extends CreateRecord
{
    protected static string $resource = HeirCertificateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        $record = $this->getRecord();

        return Notification::make()
            ->success()
            ->title('Heir Certificate Created')
            ->body("Certificate {$record->formatted_certificate_number} has been created successfully.");
    }
}
