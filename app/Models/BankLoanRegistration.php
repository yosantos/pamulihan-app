<?php

namespace App\Models;

use App\Enums\CertificateStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankLoanRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'year',
        'date',
        'name',
        'birthplace',
        'birthdate',
        'occupation',
        'address',
        'village_id',
        'bank',
        'kohir',
        'persil',
        'nib',
        'no_shm',
        'land_of_area',
        'note',
        'status',
        'person_in_charge_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'birthdate' => 'date',
        'status' => CertificateStatus::class,
        'registration_number' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($registration) {
            if (empty($registration->registration_number)) {
                $year = $registration->year ?? date('Y');
                $registration->registration_number = static::generateRegistrationNumber($year);
            }

            if (empty($registration->year)) {
                $registration->year = date('Y');
            }

            if (empty($registration->status)) {
                $registration->status = CertificateStatus::ON_PROGRESS;
            }

            if (empty($registration->created_by)) {
                $registration->created_by = auth()->id();
            }
        });

        static::updating(function ($registration) {
            $registration->updated_by = auth()->id();
        });
    }

    /**
     * Generate registration number for the given year.
     */
    private static function generateRegistrationNumber(int $year): int
    {
        $lastRegistration = static::where('year', $year)
            ->orderBy('registration_number', 'desc')
            ->first();

        if ($lastRegistration) {
            return $lastRegistration->registration_number + 1;
        }

        return 1;
    }

    /**
     * Get the full registration number (e.g., 001/2025).
     */
    public function getFullRegistrationNumberAttribute(): string
    {
        return sprintf('%03d/%d', $this->registration_number, $this->year);
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
