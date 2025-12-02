<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NationalIdApplicant extends Model
{
    protected $fillable = [
        'no_register',
        'date',
        'national_id_number',
        'name',
        'address',
        'village_id',
        'sex',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($applicant) {
            if (empty($applicant->no_register)) {
                $applicant->no_register = static::generateRegistrationNumber();
            }
        });
    }

    /**
     * Generate registration number that resets every year.
     * Format: 001/2025, 002/2025, etc.
     */
    private static function generateRegistrationNumber(): string
    {
        $currentYear = date('Y');

        // Get the last registration number for current year
        $lastApplicant = static::where('no_register', 'like', '%/' . $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastApplicant) {
            // Extract the number part
            $parts = explode('/', $lastApplicant->no_register);
            $lastNumber = (int) $parts[0];
            $newNumber = $lastNumber + 1;
        } else {
            // First registration of the year
            $newNumber = 1;
        }

        // Format: 001/2025
        return sprintf('%03d/%s', $newNumber, $currentYear);
    }

    /**
     * Get the village that owns this applicant.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}
