<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clients extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstName',
        'lastName',
        'address',
        'phoneNumber',
        'email',
        'propertyManaged'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'propertyManaged' => 'boolean',
    ];

    /**
     * Get the client's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->firstName} {$this->lastName}";
    }

    /**
     * Scope a query to only include property managed clients.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePropertyManaged($query)
    {
        return $query->where('propertyManaged', true);
    }

    /**
     * Scope a query to only include non-property managed clients.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotPropertyManaged($query)
    {
        return $query->where('propertyManaged', false);
    }

}
