<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterCLandTitle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'village_id',
        'number_of_c',
        'number_of_persil',
        'class',
        'land_area',
        'date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'land_area' => 'decimal:2',
    ];

    /**
     * Get the village that this Letter C belongs to.
     */
    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * Get all land titles that reference this Letter C.
     */
    public function landTitles(): HasMany
    {
        return $this->hasMany(LandTitle::class);
    }
}
