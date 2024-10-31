<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cases extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'status',
        'clientId',
        'suitNumber',
        'startDate',
        'nextAdjournedDate',
        'assignedCourt',
    ];

    public function client()
    {
        return $this->belongsTo(clients::class, 'clientId');
    }
}
