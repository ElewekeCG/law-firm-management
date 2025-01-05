<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;

    protected $fillable = [
        'propertyId',
        'generated_by',
        'type',
        'report_data',
        'startDate',
        'endDate',
    ];

    // Accessor for report_data to convert JSON to array
    protected $casts = [
        'report_data' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(properties::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
