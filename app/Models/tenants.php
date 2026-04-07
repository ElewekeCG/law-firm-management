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
        return $this->belongsTo(Properties::class, 'propertyId');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'tenantId');
    }

    public function getFullNameAttribute()
    {
        return "{$this->firstName} {$this->lastName}";
    }

}
