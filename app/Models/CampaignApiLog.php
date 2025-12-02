<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignApiLog extends Model
{
    protected $fillable = [
        'campaign_id',
        'phone',
        'request_data',
        'response_status',
        'error_message',
        'ip_address',
    ];

    protected $casts = [
        'request_data' => 'array',
    ];

    /**
     * Get the campaign that owns this log.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(WhatsAppCampaign::class, 'campaign_id');
    }
}
