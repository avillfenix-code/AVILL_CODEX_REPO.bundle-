<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvillManualFare extends Model
{
    const PRICING_MODE_FIXED = 'tarifa_fija';
    const PRICING_MODE_MANUAL_QUOTE = 'cotizacion_manual';

    protected $fillable = [
        'origin_area_id',
        'destination_area_id',
        'service_type',
        'vehicle_mode',
        'pricing_mode',
        'base_amount',
        'minimum_amount',
        'management_surcharge_amount',
        'night_surcharge_amount',
        'holiday_surcharge_amount',
        'rain_surcharge_amount',
        'additional_km_amount',
        'starts_at',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'base_amount'                 => 'float',
        'minimum_amount'              => 'float',
        'management_surcharge_amount' => 'float',
        'night_surcharge_amount'      => 'float',
        'holiday_surcharge_amount'    => 'float',
        'rain_surcharge_amount'       => 'float',
        'additional_km_amount'        => 'float',
        'is_active'                   => 'boolean',
        'starts_at'                   => 'date',
        'ends_at'                     => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function originArea()
    {
        return $this->belongsTo(AvillServiceArea::class, 'origin_area_id');
    }

    public function destinationArea()
    {
        return $this->belongsTo(AvillServiceArea::class, 'destination_area_id');
    }
}
