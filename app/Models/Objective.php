<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
    use HasFactory;
 // Fillable properties for mass assignment
 protected $fillable = [
    'user_id', // Allow mass assignment for user_id
    'purpose',
    'period_id',
    'target_id',
    'objective',
];

// Define relationships if needed
public function period()
{
    return $this->belongsTo(Period::class);
}
public function target()
{
    return $this->belongsTo(Target::class);
}


public function user()
{
    return $this->belongsTo(User::class);
}
    
public function initiatives()
{
    return $this->hasMany(Initiative::class, 'objective_id');
}
}
