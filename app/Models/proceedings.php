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
        'requiredDoc',
        'dueDate',
        'docStatus'
    ];

    public function case()
    {
        return $this->belongsTo(cases::class, 'caseId');
    }
}
