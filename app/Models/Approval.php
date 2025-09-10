<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = ['user_id','period_id','approved_by','status','comment'];

    public function user() { return $this->belongsTo(User::class); }
    public function period() { return $this->belongsTo(Period::class); }
    public function manager() { return $this->belongsTo(User::class, 'approved_by'); }
}
