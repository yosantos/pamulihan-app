<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandTitleRecipient extends Model
{
    protected $fillable = [
        'land_title_id',
        'user_id',
        'type',
        'amount',
        'percentage',
        'calculated_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'percentage' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
    ];

    public function landTitle(): BelongsTo
    {
        return $this->belongsTo(LandTitle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
