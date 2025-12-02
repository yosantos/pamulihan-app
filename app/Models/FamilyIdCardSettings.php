<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyIdCardSettings extends Model
{
    protected $fillable = [
        'registration_campaign_id',
        'rejection_campaign_id',
        'completion_campaign_id',
    ];

    /**
     * Get the registration campaign.
     */
    public function registrationCampaign(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCampaign::class, 'registration_campaign_id');
    }

    /**
     * Get the rejection campaign.
     */
    public function rejectionCampaign(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCampaign::class, 'rejection_campaign_id');
    }

    /**
     * Get the completion campaign.
     */
    public function completionCampaign(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCampaign::class, 'completion_campaign_id');
    }

    /**
     * Get the singleton settings instance.
     */
    public static function get(): self
    {
        return static::firstOrCreate([]);
    }
}
