<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'paymentDate',
        'type',
        'subType',
        'tenantId',
        'clientId',
        'propertyId',
        'narration',
    ];

    public function tenant()
    {
        return $this->belongsTo(tenants::class, 'tenantId');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'clientId');
    }

    public function property()
    {
        return $this->belongsTo(properties::class, 'propertyId');
    }
}
