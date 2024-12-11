<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    protected $fillable = [
        'suitNumber',
        'clientId',
        'lawyerId',
        'title',
        'type',
        'status',
        'startDate',
        'nextAdjournedDate',
        'assignedCourt',
    ];

    protected $casts = [
        'startDate' => 'date',
        'nextAdjournedDate' => 'date'
    ];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyerId');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'clientId');
    }

    public function appointments()
    {
        return $this->hasMany(Appointments::class, 'caseId');
    }
}
