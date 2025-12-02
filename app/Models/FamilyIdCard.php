<?php

namespace App\Models;

use App\Enums\CertificateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FamilyIdCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_registration',
        'name',
        'date',
        'due_date',
        'national_id_number',
        'address',
        'village_id',
        'phone_number',
        'note',
        'status',
        'admin_memo',
        'rejection_reason',
        'person_in_charge_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'status' => CertificateStatus::class,
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($card) {
            if (empty($card->no_registration)) {
                $card->no_registration = static::generateRegistrationNumber();
            }

            // Auto-calculate due_date (date + 7 days)
            if (empty($card->due_date) && !empty($card->date)) {
                $card->due_date = Carbon::parse($card->date)->addDays(7);
            }

            if (empty($card->status)) {
                $card->status = CertificateStatus::ON_PROGRESS;
            }

            if (empty($card->created_by)) {
                $card->created_by = auth()->id();
            }
        });

        static::updating(function ($card) {
            $card->updated_by = auth()->id();

            // Update due_date if date changes
            if ($card->isDirty('date')) {
                $card->due_date = Carbon::parse($card->date)->addDays(7);
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

        $lastCard = static::where('no_registration', 'like', '%/' . $currentYear)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCard) {
            $parts = explode('/', $lastCard->no_registration);
            $lastNumber = (int) $parts[0];
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return sprintf('%03d/%s', $newNumber, $currentYear);
    }

    /**
     * Get the village relationship.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Get the person in charge.
     */
    public function personInCharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'person_in_charge_id');
    }

    /**
     * Get the creator.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the updater.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
