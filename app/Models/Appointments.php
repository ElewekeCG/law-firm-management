<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawyerId',
        'clientId',
        'caseId',
        'title',
        'description',
        'startTime',
        'endTime',
        'type',
        'status',
        'location',
        'notes'
    ];

    protected $casts = [
        'startTime' => 'datetime',
        'endTime' => 'datetime'
    ];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyerId');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'clientId');
    }

    public function case()
    {
        return $this->belongsTo(Cases::class, 'caseId');
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('startTime', '>', now())
                     ->orderBy('startTime', 'asc');
    }

    public function scopeForLawyer($query, $lawyerId)
    {
        return $query->where('lawyerId', $lawyerId);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('clientId', $clientId);
    }

    // Helpers
    public function isDuration($minutes = 30)
    {
        return $this->startTime->diffInMinutes($this->endTime) === $minutes;
    }


}
