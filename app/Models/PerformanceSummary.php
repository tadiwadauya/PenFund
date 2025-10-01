<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'period_id',
        'total_self_label',
        'total_assessor_label',
        'total_reviewer_label',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }
    public function authorisation()
    {
        return $this->hasOne(Authorisation::class, 'user_id', 'user_id')
                    ->whereColumn('period_id', 'performance_summaries.period_id');
    }
}
