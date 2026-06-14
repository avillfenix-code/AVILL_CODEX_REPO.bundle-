<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvillServiceArea extends Model
{
    protected $fillable = [
        'name',
        'type',
        'city',
        'department',
        'country_code',
        'map_polygon',
        'is_active',
    ];

    protected $casts = [
        'map_polygon' => 'array',
        'is_active'   => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
