<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class properties extends Model
{
    use HasFactory;

    protected $fillable = [
        'clientId',
        'address',
        'rate',
        'percentage',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'clientId');
    }
}
