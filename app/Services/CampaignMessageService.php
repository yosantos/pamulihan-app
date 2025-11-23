<?php

namespace App\Services;

use App\Models\WhatsAppCampaign;
use App\Models\WhatsAppMessage;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * Campaign Message Service
 *
 * Provides a reusable interface for sending WhatsApp messages using campaign templates
 * from anywhere in the application (Resources, Controllers, Jobs, Events, etc.)
 *
 * @package App\Services
 */
class CampaignMessageService
{
    protected WhatsAppService $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Send a WhatsApp message using a campaign template by campaign ID
     *
     * @param int $campaignId The campaign ID to use
     * @param string $phoneNumber The recipient's phone number
     * @param array $variables Array of variable_name => value pairs for template replacement
     * @param int|null $userId The user ID who triggered this message (defaults to auth user)
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function sendCampaignMessage(
        int $campaignId,
        string $phoneNumber,
        array $variables = [],
        ?int $userId = null
    ): array {
        DB::beginTransaction();

        try {
            // Get campaign by ID
            $campaign = $this->getCampaignById($campaignId);

            if (!$campaign) {
                return [
                    'success' => false,
                    'message' => "Campaign with ID {$campaignId} not found",
                    'data' => null,
                ];
            }

            return $this->processCampaignMessage($campaign, $phoneNumber, $variables, $userId);

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Campaign message send failed', [
                'campaign_id' => $campaignId,
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send campaign message: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Send a WhatsApp message using a campaign template by campaign name
     *
     * @param string $campaignName The campaign name to use
     * @param string $phoneNumber The recipient's phone number
     * @param array $variables Array of variable_name => value pairs for template replacement
     * @param int|null $userId The user ID who triggered this message (defaults to auth user)
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function sendCampaignByName(
        string $campaignName,
        string $phoneNumber,
        array $variables = [],
        ?int $userId = null
    ): array {
        DB::beginTransaction();

        try {
            // Get campaign by name
            $campaign = $this->getCampaignByName($campaignName);

            if (!$campaign) {
                return [
                    'success' => false,
                    'message' => "Campaign '{$campaignName}' not found",
                    'data' => null,
                ];
            }

            return $this->processCampaignMessage($campaign, $phoneNumber, $variables, $userId);

        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Campaign message send failed', [
                'campaign_name' => $campaignName,
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send campaign message: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Process and send a campaign message
     * Internal method that handles the actual sending logic
     *
     * @param WhatsAppCampaign $campaign
     * @param string $phoneNumber
     * @param array $variables
     * @param int|null $userId
     * @return array
     * @throws Exception
     */
    protected function processCampaignMessage(
        WhatsAppCampaign $campaign,
        string $phoneNumber,
        array $variables,
        ?int $userId
    ): array {
        // Validate campaign is active
        if (!$campaign->isActive()) {
            return [
                'success' => false,
                'message' => "Campaign '{$campaign->name}' is not active",
                'data' => null,
            ];
        }

        // Validate phone number
        if (!$this->whatsAppService->validatePhoneNumber($phoneNumber)) {
            return [
                'success' => false,
                'message' => "Invalid phone number format: {$phoneNumber}",
                'data' => null,
            ];
        }

        // Validate required variables
        $missingVariables = $campaign->validateVariables($variables);
        if (!empty($missingVariables)) {
            return [
                'success' => false,
                'message' => 'Missing required variables: ' . implode(', ', $missingVariables),
                'data' => ['missing_variables' => $missingVariables],
            ];
        }

        // Replace variables in template
        $message = $campaign->replaceVariables($variables);

        // Create WhatsAppMessage record (pending status)
        $whatsAppMessage = WhatsAppMessage::create([
            'phone_number' => $phoneNumber,
            'message' => $message,
            'campaign_id' => $campaign->id,
            'variables_used' => $variables,
            'status' => 'pending',
            'created_by' => $userId ?? auth()->id(),
            'retry_count' => 0,
        ]);

        try {
            // Send the message via WhatsApp service
            $sendResult = $this->whatsAppService->send($phoneNumber, $message);

            // Mark as sent
            $whatsAppMessage->markAsSent($userId ?? auth()->id());

            // Increment campaign usage
            $campaign->incrementUsage();

            DB::commit();

            Log::info('Campaign message sent successfully', [
                'campaign_id' => $campaign->id,
                'campaign_name' => $campaign->name,
                'message_id' => $whatsAppMessage->id,
                'phone' => $phoneNumber,
            ]);

            return [
                'success' => true,
                'message' => 'Campaign message sent successfully',
                'data' => [
                    'message_id' => $whatsAppMessage->id,
                    'campaign_id' => $campaign->id,
                    'campaign_name' => $campaign->name,
                    'phone_number' => $phoneNumber,
                    'whatsapp_response' => $sendResult['data'] ?? null,
                ],
            ];

        } catch (Exception $e) {
            // Mark as failed
            $whatsAppMessage->markAsFailed($e->getMessage());

            DB::commit(); // Commit the failed record

            throw $e;
        }
    }

    /**
     * Get campaign by name
     *
     * @param string $name The campaign name
     * @return WhatsAppCampaign|null
     */
    public function getCampaignByName(string $name): ?WhatsAppCampaign
    {
        return WhatsAppCampaign::where('name', $name)->first();
    }

    /**
     * Get campaign by ID
     *
     * @param int $id The campaign ID
     * @return WhatsAppCampaign|null
     */
    public function getCampaignById(int $id): ?WhatsAppCampaign
    {
        return WhatsAppCampaign::find($id);
    }

    /**
     * Check if a campaign exists and is active
     *
     * @param string $campaignName The campaign name
     * @return bool
     */
    public function campaignExistsAndActive(string $campaignName): bool
    {
        return WhatsAppCampaign::where('name', $campaignName)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get list of all active campaigns
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveCampaigns()
    {
        return WhatsAppCampaign::active()
            ->select('id', 'name', 'description', 'variables')
            ->get();
    }

    /**
     * Get campaign variables (required placeholders)
     *
     * @param string $campaignName The campaign name
     * @return array|null
     */
    public function getCampaignVariables(string $campaignName): ?array
    {
        $campaign = $this->getCampaignByName($campaignName);

        if (!$campaign) {
            return null;
        }

        return $campaign->getDynamicVariables();
    }

    /**
     * Validate if all required variables are provided for a campaign
     *
     * @param string $campaignName The campaign name
     * @param array $variables The variables to validate
     * @return array ['valid' => bool, 'missing_variables' => array]
     */
    public function validateCampaignVariables(string $campaignName, array $variables): array
    {
        $campaign = $this->getCampaignByName($campaignName);

        if (!$campaign) {
            return [
                'valid' => false,
                'missing_variables' => [],
                'error' => "Campaign '{$campaignName}' not found",
            ];
        }

        $missingVariables = $campaign->validateVariables($variables);

        return [
            'valid' => empty($missingVariables),
            'missing_variables' => $missingVariables,
        ];
    }

    /**
     * Send bulk campaign messages to multiple recipients
     *
     * @param string $campaignName The campaign name
     * @param array $recipients Array of ['phone' => '', 'variables' => []]
     * @param int|null $userId The user ID who triggered this message
     * @return array ['success_count' => int, 'failed_count' => int, 'results' => array]
     */
    public function sendBulkCampaign(
        string $campaignName,
        array $recipients,
        ?int $userId = null
    ): array {
        $results = [];
        $successCount = 0;
        $failedCount = 0;

        foreach ($recipients as $recipient) {
            $phone = $recipient['phone'] ?? null;
            $variables = $recipient['variables'] ?? [];

            if (!$phone) {
                $results[] = [
                    'phone' => 'N/A',
                    'success' => false,
                    'message' => 'Phone number is required',
                ];
                $failedCount++;
                continue;
            }

            $result = $this->sendCampaignByName($campaignName, $phone, $variables, $userId);

            $results[] = [
                'phone' => $phone,
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data'] ?? null,
            ];

            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'total' => count($recipients),
            'results' => $results,
        ];
    }

    /**
     * Preview message with variables replaced
     * Does not send the message
     *
     * @param string $campaignName The campaign name
     * @param array $variables The variables for replacement
     * @return array ['success' => bool, 'message' => string, 'preview' => string|null]
     */
    public function previewCampaignMessage(string $campaignName, array $variables = []): array
    {
        $campaign = $this->getCampaignByName($campaignName);

        if (!$campaign) {
            return [
                'success' => false,
                'message' => "Campaign '{$campaignName}' not found",
                'preview' => null,
            ];
        }

        if (!$campaign->isActive()) {
            return [
                'success' => false,
                'message' => "Campaign '{$campaign->name}' is not active",
                'preview' => null,
            ];
        }

        // Validate variables
        $missingVariables = $campaign->validateVariables($variables);
        if (!empty($missingVariables)) {
            return [
                'success' => false,
                'message' => 'Missing required variables: ' . implode(', ', $missingVariables),
                'preview' => null,
                'missing_variables' => $missingVariables,
            ];
        }

        $preview = $campaign->replaceVariables($variables);

        return [
            'success' => true,
            'message' => 'Preview generated successfully',
            'preview' => $preview,
            'campaign_name' => $campaign->name,
            'campaign_id' => $campaign->id,
        ];
    }

    /**
     * Facade helper methods for cleaner syntax
     */

    /**
     * Alias for sendCampaignByName - used by facade
     */
    public function send(string $campaignName, string $phoneNumber, array $variables = [], ?int $userId = null): array
    {
        return $this->sendCampaignByName($campaignName, $phoneNumber, $variables, $userId);
    }

    /**
     * Alias for sendCampaignMessage - used by facade
     */
    public function sendById(int $campaignId, string $phoneNumber, array $variables = [], ?int $userId = null): array
    {
        return $this->sendCampaignMessage($campaignId, $phoneNumber, $variables, $userId);
    }

    /**
     * Alias for previewCampaignMessage - used by facade
     */
    public function preview(string $campaignName, array $variables = []): array
    {
        return $this->previewCampaignMessage($campaignName, $variables);
    }

    /**
     * Alias for campaignExistsAndActive - used by facade
     */
    public function isAvailable(string $campaignName): bool
    {
        return $this->campaignExistsAndActive($campaignName);
    }

    /**
     * Alias for getActiveCampaigns - used by facade
     */
    public function active()
    {
        return $this->getActiveCampaigns();
    }

    /**
     * Alias for getCampaignVariables - used by facade
     */
    public function variables(string $campaignName): ?array
    {
        return $this->getCampaignVariables($campaignName);
    }

    /**
     * Alias for validateCampaignVariables - used by facade
     */
    public function validate(string $campaignName, array $variables): array
    {
        return $this->validateCampaignVariables($campaignName, $variables);
    }

    /**
     * Alias for sendBulkCampaign - used by facade
     */
    public function sendBulk(string $campaignName, array $recipients, ?int $userId = null): array
    {
        return $this->sendBulkCampaign($campaignName, $recipients, $userId);
    }

    /**
     * Alias for getCampaignByName - used by facade
     */
    public function getByName(string $name): ?WhatsAppCampaign
    {
        return $this->getCampaignByName($name);
    }

    /**
     * Alias for getCampaignById - used by facade
     */
    public function getById(int $id): ?WhatsAppCampaign
    {
        return $this->getCampaignById($id);
    }
}
