<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointments::class,
            $this->role === 'lawyer' ? 'lawyerId' : 'clientId'
        );
    }

    public function cases()
    {
        return $this->hasMany(Cases::class, 'lawyerId');
    }

    public function scopeLawyers($query)
    {
        return $query->where('role', 'lawyer');
    }

    public function scopeClients($query)
    {
        return $query->where('role', 'client');
    }

    public function scopeClerk($query)
    {
        return $query->where('role', 'clerk');
    }

    // Role-based access methods
    public function isLawyer()
    {
        return $this->role === 'lawyer';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isClerk()
    {
        return $this->role === 'clerk';
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function notifications()
    {
        return $this->morphMany(\App\Models\Notifications::class, 'notifiable');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
}
