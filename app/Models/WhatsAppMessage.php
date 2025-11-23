<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'phone_number',
        'message',
        'campaign_id',
        'variables_used',
        'status',
        'error_message',
        'sent_at',
        'retry_count',
        'created_by',
        'sent_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sent_at' => 'datetime',
        'variables_used' => 'array',
    ];

    /**
     * Get the truncated message for preview.
     *
     * @return string
     */
    public function getMessagePreviewAttribute(): string
    {
        return strlen($this->message) > 50
            ? substr($this->message, 0, 50) . '...'
            : $this->message;
    }

    /**
     * Scope a query to only include sent messages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope a query to only include failed messages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Check if the message was sent successfully.
     *
     * @return bool
     */
    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Check if the message failed to send.
     *
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Get the user who created this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who sent this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    /**
     * Increment the retry count for this message.
     *
     * @return void
     */
    public function incrementRetryCount(): void
    {
        $this->increment('retry_count');
    }

    /**
     * Reset the message to sent status.
     *
     * @param int|null $sentBy
     * @return void
     */
    public function markAsSent(?int $sentBy = null): void
    {
        $data = [
            'status' => 'sent',
            'sent_at' => now(),
            'error_message' => null,
        ];

        if ($sentBy !== null) {
            $data['sent_by'] = $sentBy;
        }

        $this->update($data);
    }

    /**
     * Mark the message as failed with an error message.
     *
     * @param string $errorMessage
     * @return void
     */
    public function markAsFailed(string $errorMessage): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Get the campaign associated with this message.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function campaign()
    {
        return $this->belongsTo(WhatsAppCampaign::class, 'campaign_id');
    }

    /**
     * Check if this message was sent via a campaign.
     *
     * @return bool
     */
    public function hasCampaign(): bool
    {
        return $this->campaign_id !== null;
    }

    /**
     * Scope a query to only include campaign messages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCampaign($query)
    {
        return $query->whereNotNull('campaign_id');
    }

    /**
     * Scope a query to only include non-campaign messages.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutCampaign($query)
    {
        return $query->whereNull('campaign_id');
    }
}
