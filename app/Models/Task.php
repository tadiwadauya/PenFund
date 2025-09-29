<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'section_id'];

    public function section() {
        return $this->belongsTo(EvaluationSection::class, 'section_id');
    }

    public function ratings() {
        return $this->hasMany(Rating::class);
    }
  


    
}
