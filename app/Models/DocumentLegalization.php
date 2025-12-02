<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentLegalization extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_legalization',
        'date',
        'type_of_document',
        'name',
        'occupation',
        'address',
        'village_id',
        'main_content_of_document',
        'note',
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

        static::creating(function ($legalization) {
            if (empty($legalization->number_legalization)) {
                $legalization->number_legalization = static::generateLegalizationNumber();
            }
        });
    }

    /**
     * Generate legalization number that resets every year.
     * Format: 001/2025, 002/2025, etc.
     */
    private static function generateLegalizationNumber(): string
    {
        $currentYear = date('Y');

        // Get the last legalization number for current year
        $lastLegalization = static::where('number_legalization', 'like', '%/' . $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastLegalization) {
            // Extract the number part
            $parts = explode('/', $lastLegalization->number_legalization);
            $lastNumber = (int) $parts[0];
            $newNumber = $lastNumber + 1;
        } else {
            // First legalization of the year
            $newNumber = 1;
        }

        // Format: 001/2025
        return sprintf('%03d/%s', $newNumber, $currentYear);
    }

    /**
     * Get the village that owns this legalization.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}
