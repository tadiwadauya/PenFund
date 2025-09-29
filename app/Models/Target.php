<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Target extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'target_name',
        'user_id',
        'period_id',
        'self_rating',
        'self_comment',
        'assessor_rating',
        'assessor_comment',
        'reviewer_rating',
        'reviewer_comment',
    ];
    
    /**
     * Get the objectives associated with the target.
     */
    public function objectives(): HasMany
    {
        return $this->hasMany(Objective::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

public function period()
{
    return $this->belongsTo(Period::class);
}

}
