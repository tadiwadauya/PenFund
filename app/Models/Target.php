<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Target extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'target_name'
    ];

    /**
     * Get the objectives associated with the target.
     */
    public function objectives(): HasMany
    {
        return $this->hasMany(Objective::class);
    }
}
