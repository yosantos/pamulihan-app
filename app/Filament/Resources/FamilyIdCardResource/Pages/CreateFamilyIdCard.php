<?php

namespace App\Filament\Resources\FamilyIdCardResource\Pages;

use App\Filament\Resources\FamilyIdCardResource;
use App\Models\FamilyIdCardSettings;
use App\Models\WhatsAppCampaign;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateFamilyIdCard extends CreateRecord
{
    protected static string $resource = FamilyIdCardResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('family_id_card.notifications.created');
    }


    protected function afterCreate(): void
    {
        // Get settings
        $settings = FamilyIdCardSettings::get();

        // Send WhatsApp notification if campaign is configured
        if ($settings->registration_campaign_id) {
            $campaign = WhatsAppCampaign::find($settings->registration_campaign_id);

            if ($campaign) {
                $variables = [];
                if (!empty($campaign->variables)) {
                    foreach ($campaign->variables as $variable) {
                        $value = match($variable) {
                            'name' => $this->record->name,
                            'phone_number' => $this->record->phone_number,
                            'no_registration' => $this->record->no_registration,
                            'date' => $this->record->date->format('d M Y'),
                            'due_date' => $this->record->due_date->format('d M Y'),
                            default => null,
                        };

                        if ($value !== null) {
                            $variables[$variable] = $value;
                        }
                    }
                }

                $result = \App\Facades\Campaign::send(
                    $campaign->name,
                    $this->record->phone_number,
                    $variables
                );

                if ($result['success']) {
                    Notification::make()
                        ->success()
                        ->title(__('family_id_card.notifications.created_and_notified'))
                        ->body(__('family_id_card.notifications.whatsapp_sent', [
                            'registration' => $this->record->no_registration
                        ]))
                        ->send();
                } else {
                    Notification::make()
                        ->warning()
                        ->title(__('family_id_card.notifications.created_notification_failed'))
                        ->body(__('family_id_card.notifications.whatsapp_failed_detail', [
                            'error' => $result['message'] ?? 'Unknown error'
                        ]))
                        ->persistent()
                        ->send();
                }
            }
        }
    }
}
