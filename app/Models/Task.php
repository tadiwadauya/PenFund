<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'period_id',
        'user_id',
        'key_task',
        'task',
        'target',
        'objective',
        'self_rating',
        'self_comment',
        'assessor_rating',
        'assessor_comment',
        'reviewer_rating',
        'reviewer_comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
}
