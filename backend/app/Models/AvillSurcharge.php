<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvillSurcharge extends Model
{
    protected $fillable = [
        'name',
        'service_type',
        'vehicle_mode',
        'amount',
        'applies_night',
        'applies_sunday',
        'applies_holiday',
        'night_starts_at',
        'night_ends_at',
        'is_active',
    ];

    protected $casts = [
        'amount'          => 'float',
        'applies_night'   => 'boolean',
        'applies_sunday'  => 'boolean',
        'applies_holiday' => 'boolean',
        'is_active'       => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
