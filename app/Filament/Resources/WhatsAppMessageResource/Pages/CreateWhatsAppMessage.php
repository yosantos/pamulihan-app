<?php

namespace App\Filament\Resources\WhatsAppMessageResource\Pages;

use App\Filament\Resources\WhatsAppMessageResource;
use App\Models\WhatsAppMessage;
use App\Services\WhatsAppService;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateWhatsAppMessage extends CreateRecord
{
    protected static string $resource = WhatsAppMessageResource::class;

    protected static bool $canCreateAnother = false;

    /**
     * Mutate form data before filling
     *
     * @return array
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Check if campaign parameter is passed
        if (request()->has('campaign')) {
            $data['use_campaign'] = true;
            $data['campaign_id'] = request()->get('campaign');
        }

        return $data;
    }

    /**
     * Handle record creation and send WhatsApp message
     *
     * @param array $data
     * @return Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        $whatsappService = app(WhatsAppService::class);
        $phoneNumber = $data['phone_number'];
        $useCampaign = $data['use_campaign'] ?? false;
        $campaignId = $data['campaign_id'] ?? null;
        $message = null;
        $variablesUsed = [];

        // Process campaign message
        if ($useCampaign && $campaignId) {
            $campaign = \App\Models\WhatsAppCampaign::find($campaignId);

            if (!$campaign) {
                Notification::make()
                    ->danger()
                    ->title('Campaign Not Found')
                    ->body('The selected campaign could not be found.')
                    ->send();

                $this->halt();
            }

            // Collect variable values
            foreach ($campaign->variables ?? [] as $variable) {
                $variableKey = 'variable_' . $variable;
                if (isset($data[$variableKey])) {
                    $variablesUsed[$variable] = $data[$variableKey];
                }
            }

            // Validate all required variables are provided
            $missing = $campaign->validateVariables($variablesUsed);
            if (!empty($missing)) {
                Notification::make()
                    ->danger()
                    ->title('Missing Variables')
                    ->body('Please provide values for: ' . implode(', ', $missing))
                    ->send();

                $this->halt();
            }

            // Generate message from campaign
            $message = $campaign->replaceVariables($variablesUsed);

            // Increment campaign usage
            $campaign->incrementUsage();
        } else {
            $message = $data['message'];
        }

        try {
            // Validate phone number using WhatsAppService
            if (!$whatsappService->validatePhoneNumber($phoneNumber)) {
                Notification::make()
                    ->danger()
                    ->title('Invalid Phone Number')
                    ->body('Please provide a valid Indonesian phone number.')
                    ->send();

                $this->halt();
            }

            // Send WhatsApp message
            $response = $whatsappService->send($phoneNumber, $message);

            // Create record with success status
            $record = static::getModel()::create([
                'phone_number' => $phoneNumber,
                'message' => $message,
                'campaign_id' => $useCampaign ? $campaignId : null,
                'variables_used' => $useCampaign ? $variablesUsed : null,
                'status' => 'sent',
                'error_message' => null,
                'sent_at' => now(),
                'created_by' => Filament::auth()->id(),
                'sent_by' => Filament::auth()->id(),
            ]);

            // Show success notification
            Notification::make()
                ->success()
                ->title('WhatsApp Message Sent')
                ->body("Message successfully sent to {$phoneNumber}")
                ->send();

            return $record;

        } catch (\Exception $e) {
            // Log the error
            \Log::error('WhatsApp Message Send Error', [
                'phone' => $phoneNumber,
                'campaign_id' => $campaignId,
                'error' => $e->getMessage(),
            ]);

            // Create record with failed status
            $record = static::getModel()::create([
                'phone_number' => $phoneNumber,
                'message' => $message,
                'campaign_id' => $useCampaign ? $campaignId : null,
                'variables_used' => $useCampaign ? $variablesUsed : null,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'sent_at' => now(),
                'created_by' => Filament::auth()->id(),
                'sent_by' => null, // Not set yet since send failed
            ]);

            // Show error notification
            Notification::make()
                ->danger()
                ->title('Failed to Send Message')
                ->body($e->getMessage())
                ->persistent()
                ->send();

            return $record;
        }
    }

    /**
     * Get redirect URL after creation
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Get success notification message
     *
     * @return Notification
     */
    protected function getCreatedNotification(): ?Notification
    {
        // We handle notifications in handleRecordCreation, so return null here
        return null;
    }
}
