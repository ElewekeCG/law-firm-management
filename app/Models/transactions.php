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
        'entityType',
        'entityId',
        'type',
        'subType',
        'propertyId',
        'narration',
    ];

    // defining polymorphic relationship
    public function entity()
    {
        return $this->morphTo();
    }

    public function property()
    {
        return $this->belongsTo(properties::class, 'propertyId');
    }
}
