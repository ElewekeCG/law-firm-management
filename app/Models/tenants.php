<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenants extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'paymentType',
        'accomType',
        'rentAmt',
        'propertyId',
    ];

    public function property()
    {
        return $this->belongsTo(properties::class, 'propertyId');
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstName} {$this->lastName}";
    }

}
