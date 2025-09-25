<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'department',
        'section',
        'jobtitle',
        'address',
        'mobile',
        'extension',
        'speeddial',
        'gender',
        'dob',
        'grade',
        'is_admin',
        'supervisor_id', // adding this
        'reviewer_id' // adding this
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }

    public function authorisations()
    {
        return $this->hasMany(Authorisation::class);
    }
    
    public function purposes()
{
    return $this->hasMany(Purpose::class);
}

public function objectives()
{
    return $this->hasMany(Objective::class);
}

public function initiatives()
{
    return $this->hasMany(Initiative::class);
}

public function supervisor()
{
    return $this->belongsTo(User::class, 'supervisor_id');
}

public function reviewer()
{
    return $this->belongsTo(User::class, 'reviewer_id');
}


public function subordinates()
{
    return $this->hasMany(User::class, 'supervisor_id');
}
}