<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpptLandTitle extends Model
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
        'owner',
        'block',
        'village_id',
        'land_area',
        'building_area',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'land_area' => 'decimal:2',
        'building_area' => 'decimal:2',
    ];

    /**
     * Get the village that this SPPT belongs to.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Get all land titles that reference this SPPT.
     */
    public function landTitles(): HasMany
    {
        return $this->hasMany(LandTitle::class);
    }
}
