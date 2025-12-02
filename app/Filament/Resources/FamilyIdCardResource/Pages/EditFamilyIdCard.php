<?php

namespace App\Filament\Resources\FamilyIdCardResource\Pages;

use App\Filament\Resources\FamilyIdCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFamilyIdCard extends EditRecord
{
    protected static string $resource = FamilyIdCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label(__('family_id_card.actions.view')),
            Actions\DeleteAction::make()
                ->label(__('family_id_card.actions.delete')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('family_id_card.notifications.updated');
    }
}
