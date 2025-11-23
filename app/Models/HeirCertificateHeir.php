<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeirCertificateHeir extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'heir_certificate_id',
        'heir_name',
        'heir_address',
        'relationship',
    ];

    /**
     * Get the heir certificate that owns this heir.
     *
     * @return BelongsTo
     */
    public function heirCertificate(): BelongsTo
    {
        return $this->belongsTo(HeirCertificate::class);
    }
}
