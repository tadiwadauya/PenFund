<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purpose extends Model
{
    use HasFactory;

    // Fillable properties for mass assignment
    protected $fillable = [
        'user_id', // Allow mass assignment for user_id
        'purpose',
        'period_id',
    ];

    // Define relationships if needed
    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function targets()
    {
        return $this->hasMany(Target::class); // Assuming that a Purpose can have many Targets
    }
}