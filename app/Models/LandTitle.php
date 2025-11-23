<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandTitle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'number',
        'year',
        'land_title_type_id',
        'is_heir',
        'sppt_land_title_id',
        'letter_c_land_title_id',
        'transaction_amount',
        'transaction_amount_wording',
        'area_of_the_land',
        'area_of_the_land_wording',
        'pph',
        'bphtb',
        'adm',
        'pbb',
        'adm_certificate',
        'ppat_amount',
        'total_amount',
        'status',
        'paid_amount',
        'completion_number',
        'completion_year',
        'north_border',
        'east_border',
        'west_border',
        'south_border',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'number' => 'integer',
        'year' => 'integer',
        'completion_number' => 'integer',
        'completion_year' => 'integer',
        'is_heir' => 'boolean',
        'transaction_amount' => 'decimal:2',
        'area_of_the_land' => 'decimal:2',
        'pph' => 'decimal:2',
        'bphtb' => 'decimal:2',
        'adm' => 'decimal:2',
        'pbb' => 'decimal:2',
        'adm_certificate' => 'decimal:2',
        'ppat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Get the formatted land title number.
     *
     * @return string
     */
    public function getFormattedNumberAttribute(): string
    {
        if (!$this->number || !$this->year) {
            return 'Not generated';
        }

        return sprintf('%d/%d', $this->number, $this->year);
    }

    /**
     * Get the land title type.
     */
    public function landTitleType(): BelongsTo
    {
        return $this->belongsTo(LandTitleType::class);
    }

    /**
     * Get the SPPT land title.
     */
    public function spptLandTitle(): BelongsTo
    {
        return $this->belongsTo(SpptLandTitle::class);
    }

    /**
     * Get the Letter C land title.
     */
    public function letterCLandTitle(): BelongsTo
    {
        return $this->belongsTo(LetterCLandTitle::class);
    }

    /**
     * Get the user who created this land title.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all applicants for this land title.
     */
    public function landTitleApplicants(): HasMany
    {
        return $this->hasMany(LandTitleApplicant::class);
    }

    /**
     * Get applicants by type.
     */
    public function applicantsByType(string $typeName)
    {
        return $this->landTitleApplicants()
            ->whereHas('applicantType', function ($query) use ($typeName) {
                $query->where('name', $typeName);
            });
    }

    /**
     * Get all payments for this land title.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(LandTitlePayment::class);
    }

    /**
     * Get all recipients for this land title.
     */
    public function recipients(): HasMany
    {
        return $this->hasMany(LandTitleRecipient::class);
    }

    /**
     * Get the first buyer from applicants.
     */
    public function getFirstBuyerAttribute(): ?User
    {
        $firstBuyerApplicant = $this->landTitleApplicants()
            ->whereHas('applicantType', function ($query) {
                $query->where('code', 'buyer');
            })
            ->first();
        return $firstBuyerApplicant?->user;
    }

    /**
     * Get remaining amount to be paid.
     */
    public function getRemainingAmountAttribute(): float
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    /**
     * Check if fully paid.
     */
    public function isFullyPaid(): bool
    {
        return $this->paid_amount >= $this->total_amount;
    }
}
