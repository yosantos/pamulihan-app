<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Campaign Facade
 *
 * Provides easy access to CampaignMessageService throughout the application
 *
 * Example usage:
 * ```php
 * use App\Facades\Campaign;
 *
 * // Send campaign by name
 * Campaign::send('Welcome Campaign', '628123456789', [
 *     'user_name' => 'John Doe',
 *     'code' => '123456'
 * ]);
 *
 * // Send campaign by ID
 * Campaign::sendById(1, '628123456789', ['code' => '123456']);
 *
 * // Preview campaign
 * Campaign::preview('Welcome Campaign', ['user_name' => 'John']);
 *
 * // Check if campaign is available
 * Campaign::isAvailable('Welcome Campaign');
 *
 * // Get active campaigns
 * Campaign::active();
 * ```
 *
 * @method static array send(string $campaignName, string $phoneNumber, array $variables = [], ?int $userId = null)
 * @method static array sendById(int $campaignId, string $phoneNumber, array $variables = [], ?int $userId = null)
 * @method static array preview(string $campaignName, array $variables = [])
 * @method static bool isAvailable(string $campaignName)
 * @method static \Illuminate\Support\Collection active()
 * @method static array|null variables(string $campaignName)
 * @method static array validate(string $campaignName, array $variables)
 * @method static array sendBulk(string $campaignName, array $recipients, ?int $userId = null)
 * @method static \App\Models\WhatsAppCampaign|null getByName(string $name)
 * @method static \App\Models\WhatsAppCampaign|null getById(int $id)
 *
 * @see \App\Services\CampaignMessageService
 */
class Campaign extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'campaign.message.service';
    }
}
