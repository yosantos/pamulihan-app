<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandTitleApplicant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'land_title_id',
        'user_id',
        'land_title_applicant_type_id',
    ];

    /**
     * Get the land title this applicant belongs to.
     */
    public function landTitle(): BelongsTo
    {
        return $this->belongsTo(LandTitle::class);
    }

    /**
     * Get the user (applicant).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the applicant type.
     */
    public function applicantType(): BelongsTo
    {
        return $this->belongsTo(LandTitleApplicantType::class, 'land_title_applicant_type_id');
    }
}
