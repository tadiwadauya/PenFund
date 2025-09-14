<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Initiative extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', // Allow mass assignment for user_id
        'purpose',
        'period_id',
        'target_id',
        'objective_id',
        'initiative',
        'budget',
        'rating',
        'comment',
        'archieved',
    ];
    
    // Define relationships if needed
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function target()
    {
        return $this->belongsTo(Target::class);
    }

    public function objective()
    {
        return $this->belongsTo(Objective::class);
    }

    /**
     * Accessor for human-readable rating label.
     */
   
}
