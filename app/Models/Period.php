<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = [
        'year'
    ];

    /**
     * Get the contracts associated with the period.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the objectives associated with the period.
     */
    public function objectives(): HasMany
    {
        return $this->hasMany(Objective::class);
    }

    /**
     * Get the ratings associated with the period.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function averageObjectiveRating()
{
    return $this->objectives()->avg('annual_rating');
}
}
