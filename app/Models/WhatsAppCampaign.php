<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WhatsAppCampaign extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_campaigns';

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($campaign) {
            if (empty($campaign->campaign_code)) {
                $campaign->campaign_code = (string) Str::uuid();
            }
            if (empty($campaign->api_key)) {
                $campaign->api_key = (string) Str::uuid();
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'company_name',
        'template',
        'variables',
        'is_active',
        'usage_count',
        'created_by',
        'campaign_code',
        'api_key',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
        'usage_count' => 'integer',
    ];

    /**
     * Get the user who created this campaign.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all messages sent using this campaign.
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(WhatsAppMessage::class, 'campaign_id');
    }

    /**
     * Get all API logs for this campaign.
     *
     * @return HasMany
     */
    public function apiLogs(): HasMany
    {
        return $this->hasMany(CampaignApiLog::class, 'campaign_id');
    }

    /**
     * Scope a query to only include active campaigns.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive campaigns.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get all variable placeholders from the template.
     * Extracts all [variable] patterns from the template.
     *
     * @return array
     */
    public function getVariablePlaceholders(): array
    {
        preg_match_all('/\[(\w+)\]/', $this->template, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Replace variables in the template with provided values.
     * Supports both [variable] and {variable} formats.
     *
     * @param array $values Array of variable_name => value pairs
     * @return string
     */
    public function replaceVariables(array $values): string
    {
        $message = $this->template;

        // First, replace the static company name variable (both formats)
        $message = str_replace('[Name_Company]', $this->company_name, $message);
        $message = str_replace('{Name_Company}', $this->company_name, $message);

        // Then replace dynamic variables (both square brackets and curly braces)
        foreach ($values as $variable => $value) {
            $message = str_replace('[' . $variable . ']', $value, $message);
            $message = str_replace('{' . $variable . '}', $value, $message);
        }

        return $message;
    }

    /**
     * Validate that all required variables are provided.
     *
     * @param array $values Array of variable_name => value pairs
     * @return array Array of missing variables
     */
    public function validateVariables(array $values): array
    {
        $required = $this->variables ?? [];
        $missing = [];

        foreach ($required as $variable) {
            if (!isset($values[$variable]) || $values[$variable] === '') {
                $missing[] = $variable;
            }
        }

        return $missing;
    }

    /**
     * Get dynamic variables (from the variables array).
     * These are variables that need to be provided when sending.
     *
     * @return array
     */
    public function getDynamicVariables(): array
    {
        return $this->variables ?? [];
    }

    /**
     * Increment the usage count for this campaign.
     *
     * @return void
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Get the success rate for this campaign.
     *
     * @return float
     */
    public function getSuccessRate(): float
    {
        $totalMessages = $this->messages()->count();

        if ($totalMessages === 0) {
            return 0.0;
        }

        $successfulMessages = $this->messages()->where('status', 'sent')->count();

        return round(($successfulMessages / $totalMessages) * 100, 2);
    }

    /**
     * Get the last used timestamp.
     *
     * @return \Illuminate\Support\Carbon|null
     */
    public function getLastUsedAt()
    {
        return $this->messages()
            ->latest('created_at')
            ->first()
            ?->created_at;
    }

    /**
     * Get the template preview with variable placeholders highlighted.
     *
     * @return string
     */
    public function getTemplatePreview(): string
    {
        return strlen($this->template) > 100
            ? substr($this->template, 0, 100) . '...'
            : $this->template;
    }

    /**
     * Check if the campaign is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->is_active === true;
    }
}
