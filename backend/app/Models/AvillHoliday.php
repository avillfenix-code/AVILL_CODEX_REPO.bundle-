<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvillHoliday extends Model
{
    protected $fillable = [
        'name',
        'date',
        'country_code',
        'applies_to',
        'is_active',
    ];

    protected $casts = [
        'date'      => 'date',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
