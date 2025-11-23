<?php

namespace App\Filament\Resources\WhatsAppCampaignResource\Pages;

use App\Filament\Resources\WhatsAppCampaignResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppCampaign extends EditRecord
{
    protected static string $resource = WhatsAppCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
