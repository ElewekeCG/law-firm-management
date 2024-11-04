<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class proceedings extends Model
{
    use HasFactory;

    protected $fillable = [
        'caseId',
        'description',
    ];

    public function case()
    {
        return $this->belongsTo(cases::class, 'caseId');
    }
}
