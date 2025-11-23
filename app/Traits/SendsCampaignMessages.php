<?php

namespace App\Traits;

use App\Services\CampaignMessageService;

/**
 * Sends Campaign Messages Trait
 *
 * Add this trait to any model to enable easy campaign message sending
 *
 * Example usage:
 * ```php
 * class Product extends Model
 * {
 *     use SendsCampaignMessages;
 * }
 *
 * // Then use it:
 * $product->sendCampaign('Product Created', $user->phone, [
 *     'product_name' => $product->name,
 *     'price' => $product->price
 * ]);
 * ```
 *
 * @package App\Traits
 */
trait SendsCampaignMessages
{
    /**
     * Send a campaign message using campaign name
     *
     * @param string $campaignName The campaign name to use
     * @param string $phoneNumber The recipient's phone number
     * @param array $variables Array of variable_name => value pairs
     * @param int|null $userId The user ID who triggered this message (defaults to auth user)
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function sendCampaign(
        string $campaignName,
        string $phoneNumber,
        array $variables = [],
        ?int $userId = null
    ): array {
        $campaignService = app(CampaignMessageService::class);

        return $campaignService->sendCampaignByName(
            $campaignName,
            $phoneNumber,
            $variables,
            $userId ?? auth()->id()
        );
    }

    /**
     * Send a campaign message using campaign ID
     *
     * @param int $campaignId The campaign ID to use
     * @param string $phoneNumber The recipient's phone number
     * @param array $variables Array of variable_name => value pairs
     * @param int|null $userId The user ID who triggered this message
     * @return array ['success' => bool, 'message' => string, 'data' => array|null]
     */
    public function sendCampaignById(
        int $campaignId,
        string $phoneNumber,
        array $variables = [],
        ?int $userId = null
    ): array {
        $campaignService = app(CampaignMessageService::class);

        return $campaignService->sendCampaignMessage(
            $campaignId,
            $phoneNumber,
            $variables,
            $userId ?? auth()->id()
        );
    }

    /**
     * Preview a campaign message without sending it
     *
     * @param string $campaignName The campaign name
     * @param array $variables The variables for replacement
     * @return array ['success' => bool, 'message' => string, 'preview' => string|null]
     */
    public function previewCampaign(string $campaignName, array $variables = []): array
    {
        $campaignService = app(CampaignMessageService::class);

        return $campaignService->previewCampaignMessage($campaignName, $variables);
    }

    /**
     * Check if a campaign exists and is active
     *
     * @param string $campaignName The campaign name
     * @return bool
     */
    public function campaignIsAvailable(string $campaignName): bool
    {
        $campaignService = app(CampaignMessageService::class);

        return $campaignService->campaignExistsAndActive($campaignName);
    }

    /**
     * Get required variables for a campaign
     *
     * @param string $campaignName The campaign name
     * @return array|null
     */
    public function getCampaignVariables(string $campaignName): ?array
    {
        $campaignService = app(CampaignMessageService::class);

        return $campaignService->getCampaignVariables($campaignName);
    }
}
