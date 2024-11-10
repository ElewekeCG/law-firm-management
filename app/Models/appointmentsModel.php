<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class appointmentsModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientId',
        'appointmentDate',
        'fees',
        'amountPaid',
        'balance',
        'instructions',
    ];

    public function client()
    {
        return $this->belongsTo(clients::class, 'clientId');
    }
}
