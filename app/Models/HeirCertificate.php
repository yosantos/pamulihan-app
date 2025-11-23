<?php

namespace App\Models;

use App\Enums\CertificateStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeirCertificate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'certificate_number',
        'year',
        'certificate_date',
        'applicant_name',
        'applicant_address',
        'phone_number',
        'deceased_name',
        'place_of_death',
        'date_of_death',
        'status',
        'person_in_charge_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'certificate_date' => 'date',
        'date_of_death' => 'date',
        'status' => CertificateStatus::class,
        'certificate_number' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Get the heirs associated with this certificate.
     *
     * @return HasMany
     */
    public function heirs(): HasMany
    {
        return $this->hasMany(HeirCertificateHeir::class);
    }

    /**
     * Get the user who created this certificate.
     *
     * @return BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this certificate.
     *
     * @return BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the person in charge (PIC) for this certificate.
     *
     * @return BelongsTo
     */
    public function personInCharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'person_in_charge_id');
    }

    /**
     * Get the formatted certificate number.
     *
     * @return string
     */
    public function getFormattedCertificateNumberAttribute(): string
    {
        if (!$this->certificate_number || !$this->year) {
            return 'Not generated';
        }

        return sprintf('%d/%d', $this->certificate_number, $this->year);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically set created_by and updated_by
        static::creating(function ($model) {
            // Auto-generate certificate number
            $year = now()->year;
            $lastNumber = static::where('year', $year)->max('certificate_number') ?? 0;
            $model->certificate_number = $lastNumber + 1;
            $model->year = $year;

            // Set default status if not provided
            if (!isset($model->status)) {
                $model->status = CertificateStatus::ON_PROGRESS;
            }

            // Set created_by and updated_by
            if (auth()->check()) {
                $model->created_by = $model->created_by ?? auth()->id();
                $model->updated_by = auth()->id();
            }

            // Format phone number to 62xxx if it starts with 08
            if ($model->phone_number && str_starts_with($model->phone_number, '08')) {
                $model->phone_number = '62' . substr($model->phone_number, 1);
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }

            // Format phone number to 62xxx if it starts with 08
            if ($model->phone_number && str_starts_with($model->phone_number, '08')) {
                $model->phone_number = '62' . substr($model->phone_number, 1);
            }
        });
    }
}
