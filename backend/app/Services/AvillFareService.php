<?php

namespace App\Services;

use App\Models\AvillHoliday;
use App\Models\AvillManualFare;
use App\Models\AvillServiceArea;
use App\Models\AvillSurcharge;
use Carbon\Carbon;

class AvillFareService
{
    public function quoteForTrip($vehicleType, $pickup, $dropoff, ?Carbon $dateTime = null, bool $isRaining = false): ?array
    {
        $dateTime = $dateTime ?: now();
        $originArea = $this->resolveArea($pickup);
        $destinationArea = $this->resolveArea($dropoff);

        if (!$originArea) { return null; }
        if (!$destinationArea) { return null; }

        $serviceTypes = $this->tripServiceTypeCandidates($vehicleType);
        $vehicleMode = $this->vehicleModeFromVehicleType($vehicleType);
        $fare = $this->findFare($serviceTypes, $vehicleMode, $originArea->id, $destinationArea->id, $dateTime);

        if (!$fare) {
            if ($this->manualQuoteExists($serviceTypes, $vehicleMode, $originArea->id, $destinationArea->id, $dateTime)) {
                return $this->pendingQuote($originArea, $destinationArea, $serviceTypes[0], $vehicleMode, AvillManualFare::PRICING_MODE_MANUAL_QUOTE, __('avill.pending_quote'));
            }
            return null;
        }

        return $this->buildQuote($fare, $originArea, $destinationArea, $fare->service_type, $vehicleMode, $dateTime, $isRaining);
    }

    public function quoteForDelivery($stops, ?Carbon $dateTime = null, bool $isRaining = false): ?array
    {
        $dateTime = $dateTime ?: now();
        $stops = $this->normalizeStops($stops);
        if (count($stops) < 2) { return null; }

        $originArea = $this->resolveArea($stops[0]);
        $destinationArea = $this->resolveArea($stops[count($stops) - 1]);
        if (!$originArea || !$destinationArea) { return null; }

        $serviceType = 'domicilio';
        $vehicleMode = 'repartidor';
        $fare = $this->findFare($serviceType, $vehicleMode, $originArea->id, $destinationArea->id, $dateTime);

        if (!$fare) {
            if ($this->manualQuoteExists($serviceType, $vehicleMode, $originArea->id, $destinationArea->id, $dateTime)) {
                return $this->pendingQuote($originArea, $destinationArea, $serviceType, $vehicleMode, AvillManualFare::PRICING_MODE_MANUAL_QUOTE, __('avill.pending_quote'));
            }
            return null;
        }

        return $this->buildQuote($fare, $originArea, $destinationArea, $serviceType, $vehicleMode, $dateTime, $isRaining);
    }

    public function resolveArea($latLng): ?AvillServiceArea
    {
        $point = $this->parseLatLng($latLng);
        if (!$point) { return null; }

        return AvillServiceArea::active()
            ->whereNotNull('map_polygon')
            ->get()
            ->first(function ($area) use ($point) {
                return $this->pointInPolygon($point, $this->normalizePolygon($area->map_polygon));
            });
    }

    private function findFare($serviceTypes, $vehicleModes, int $originAreaId, int $destinationAreaId, Carbon $dateTime): ?AvillManualFare
    {
        $serviceTypes = (array) $serviceTypes;
        $vehicleModes = (array) $vehicleModes;

        $query = AvillManualFare::active()
            ->whereIn('service_type', $serviceTypes)
            ->whereIn('vehicle_mode', $vehicleModes)
            ->where('origin_area_id', $originAreaId)
            ->where('destination_area_id', $destinationAreaId)
            ->where('pricing_mode', '!=', AvillManualFare::PRICING_MODE_MANUAL_QUOTE)
            ->whereNotNull('base_amount');

        if (in_array('domicilio', $serviceTypes, true)) {
            $query->where('management_surcharge_amount', '>', 0);
        }

        return $query
            ->where(function ($q) use ($dateTime) { $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', $dateTime->toDateString()); })
            ->where(function ($q) use ($dateTime) { $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', $dateTime->toDateString()); })
            ->latest('starts_at')
            ->first();
    }

    private function manualQuoteExists($serviceTypes, $vehicleModes, int $originAreaId, int $destinationAreaId, Carbon $dateTime): bool
    {
        $serviceTypes = (array) $serviceTypes;
        $vehicleModes = (array) $vehicleModes;

        return AvillManualFare::active()
            ->whereIn('service_type', $serviceTypes)
            ->whereIn('vehicle_mode', $vehicleModes)
            ->where('origin_area_id', $originAreaId)
            ->where('destination_area_id', $destinationAreaId)
            ->where('pricing_mode', AvillManualFare::PRICING_MODE_MANUAL_QUOTE)
            ->where(function ($q) use ($dateTime) { $q->whereNull('starts_at')->orWhereDate('starts_at', '<=', $dateTime->toDateString()); })
            ->where(function ($q) use ($dateTime) { $q->whereNull('ends_at')->orWhereDate('ends_at', '>=', $dateTime->toDateString()); })
            ->exists();
    }

    private function buildQuote(AvillManualFare $fare, AvillServiceArea $originArea, AvillServiceArea $destinationArea, string $serviceType, string $vehicleMode, Carbon $dateTime, bool $isRaining): array
    {
        $surcharges = $this->fareLevelSurcharges($fare, $serviceType, $vehicleMode, $dateTime, $isRaining);
        $surchargeTotal = $surcharges->sum('amount');
        $baseAmount = (float) $fare->base_amount;
        $managementSurcharge = (float) $fare->management_surcharge_amount;
        $minimumAmount = $fare->minimum_amount !== null ? (float) $fare->minimum_amount : null;
        $isFixedFare = $this->isFixedPricingMode($fare->pricing_mode);
        $additionalKmAmount = $isFixedFare ? null : ($fare->additional_km_amount !== null ? (float) $fare->additional_km_amount : null);
        $total = $baseAmount + (float) $surchargeTotal;

        if ($minimumAmount !== null && $total < $minimumAmount) {
            $total = $minimumAmount;
        }

        return [
            'fare_id' => $fare->id,
            'service_type' => $serviceType,
            'vehicle_mode' => $vehicleMode,
            'pricing_mode' => $fare->pricing_mode,
            'avill_fixed_fare' => $isFixedFare,
            'origin_area_id' => $originArea->id,
            'destination_area_id' => $destinationArea->id,
            'origin_area' => $originArea->name,
            'destination_area' => $destinationArea->name,
            'base_amount' => $baseAmount,
            'management_surcharge_amount' => $managementSurcharge,
            'minimum_amount' => $minimumAmount,
            'additional_km_amount' => $additionalKmAmount,
            'surcharge_total' => (float) $surchargeTotal,
            'total' => $total,
            'pending' => false,
            'message' => null,
            'surcharges' => $surcharges->values()->all(),
        ];
    }

    private function fareLevelSurcharges(AvillManualFare $fare, string $serviceType, string $vehicleMode, Carbon $dateTime, bool $isRaining)
    {
        $surcharges = collect();
        $hasFareLevelConditionalSurcharge = false;

        if ($serviceType === 'domicilio' && (float) $fare->management_surcharge_amount > 0) {
            $surcharges->push(['id' => null, 'name' => __('avill.management_surcharge'), 'amount' => (float) $fare->management_surcharge_amount, 'type' => 'gestion']);
        }
        if ((float) $fare->night_surcharge_amount > 0 && $this->isNight($dateTime)) {
            $hasFareLevelConditionalSurcharge = true;
            $surcharges->push(['id' => null, 'name' => __('avill.night_surcharge'), 'amount' => (float) $fare->night_surcharge_amount, 'type' => 'nocturno']);
        }
        if ((float) $fare->holiday_surcharge_amount > 0 && $this->isHoliday($serviceType, $vehicleMode, $dateTime)) {
            $hasFareLevelConditionalSurcharge = true;
            $surcharges->push(['id' => null, 'name' => __('avill.holiday_surcharge'), 'amount' => (float) $fare->holiday_surcharge_amount, 'type' => 'festivo']);
        }
        if ((float) $fare->rain_surcharge_amount > 0 && $isRaining) {
            $hasFareLevelConditionalSurcharge = true;
            $surcharges->push(['id' => null, 'name' => __('avill.rain_surcharge'), 'amount' => (float) $fare->rain_surcharge_amount, 'type' => 'lluvia']);
        }

        if ($hasFareLevelConditionalSurcharge) { return $surcharges; }

        return $surcharges->merge($this->activeSurcharges($serviceType, $vehicleMode, $dateTime)
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'amount' => (float) $s->amount]));
    }

    private function activeSurcharges(string $serviceType, string $vehicleMode, Carbon $dateTime)
    {
        $isSunday = $dateTime->isSunday();
        $isHoliday = $this->isHoliday($serviceType, $vehicleMode, $dateTime);

        return AvillSurcharge::active()
            ->where('service_type', $serviceType)
            ->where('vehicle_mode', $vehicleMode)
            ->get()
            ->filter(function ($s) use ($dateTime, $isSunday, $isHoliday) {
                if ($s->applies_night && $this->timeInRange($dateTime, $s->night_starts_at, $s->night_ends_at)) return true;
                if ($s->applies_sunday && $isSunday) return true;
                if ($s->applies_holiday && $isHoliday) return true;
                return false;
            });
    }

    private function isHoliday(string $serviceType, string $vehicleMode, Carbon $dateTime): bool
    {
        return AvillHoliday::active()
            ->whereDate('date', $dateTime->toDateString())
            ->where('country_code', 'CO')
            ->whereIn('applies_to', ['todos', $serviceType, $vehicleMode])
            ->exists();
    }

    private function isNight(Carbon $dateTime): bool
    {
        return $this->timeInRange($dateTime, setting('avill.night_starts_at', '20:00:00'), setting('avill.night_ends_at', '05:00:00'));
    }

    private function pendingQuote(AvillServiceArea $originArea, ?AvillServiceArea $destinationArea, string $serviceType, string $vehicleMode, ?string $pricingMode = null, ?string $message = null): array
    {
        return [
            'fare_id' => null, 'service_type' => $serviceType, 'vehicle_mode' => $vehicleMode,
            'pricing_mode' => $pricingMode, 'avill_fixed_fare' => false,
            'origin_area_id' => $originArea->id, 'destination_area_id' => $destinationArea?->id,
            'origin_area' => $originArea->name, 'destination_area' => $destinationArea?->name,
            'base_amount' => 0, 'management_surcharge_amount' => 0, 'minimum_amount' => null,
            'additional_km_amount' => null, 'surcharge_total' => 0, 'total' => 0,
            'pending' => true, 'message' => $message ?? __('avill.pending_quote'), 'surcharges' => [],
        ];
    }

    private function isFixedPricingMode(?string $pricingMode): bool
    {
        return in_array($pricingMode, [AvillManualFare::PRICING_MODE_FIXED, 'viaje_completo'], true);
    }

    private function tripServiceTypeCandidates($vehicleType): array
    {
        $name = strtolower($vehicleType->name ?? '');
        if (str_contains($name, 'motocarro')) return ['motocarro', 'motocarro_mudanzas', 'taxi_transporte_particular', 'taxi_urbano'];
        if (str_contains($name, 'mudanza') || str_contains($name, 'camion')) return ['mudanza', 'motocarro_mudanzas', 'taxi_transporte_particular', 'taxi_urbano'];
        if (str_contains($name, 'moto') || str_contains($name, 'motor')) return ['rapimoto_mototaxi', 'moto_urbana', 'taxi_urbano'];
        return ['taxi_transporte_particular', 'taxi_urbano', 'puerta_a_puerta'];
    }

    private function vehicleModeFromVehicleType($vehicleType): string
    {
        $name = strtolower($vehicleType->name ?? '');
        if (str_contains($name, 'motocarro')) return 'motocarro';
        if (str_contains($name, 'mudanza')) return 'mudanza';
        if (str_contains($name, 'camion')) return 'camion';
        if (str_contains($name, 'moto') || str_contains($name, 'motor')) return 'moto';
        return 'carro';
    }

    private function parseLatLng($latLng): ?array
    {
        if (is_array($latLng)) {
            $lat = $latLng['lat'] ?? $latLng['latitude'] ?? null;
            $lng = $latLng['lng'] ?? $latLng['long'] ?? $latLng['longitude'] ?? null;
        } else {
            $parts = explode(',', (string) $latLng);
            $lat = $parts[0] ?? null;
            $lng = $parts[1] ?? null;
        }
        if (!is_numeric($lat) || !is_numeric($lng)) return null;
        return ['lat' => (float) $lat, 'lng' => (float) $lng];
    }

    private function normalizeStops($stops): array
    {
        if (is_string($stops)) {
            $decoded = json_decode($stops, true);
            $stops = is_array($decoded) ? $decoded : [];
        }
        return is_array($stops) ? array_values($stops) : [];
    }

    private function normalizePolygon($polygon): array
    {
        $points = $polygon['points'] ?? $polygon;
        if (!is_array($points)) return [];
        return collect($points)->map(function ($p) {
            if (is_array($p) && isset($p['lat'], $p['lng'])) return ['lat' => (float)$p['lat'], 'lng' => (float)$p['lng']];
            if (is_array($p) && isset($p[0], $p[1])) return ['lat' => (float)$p[0], 'lng' => (float)$p[1]];
            return null;
        })->filter()->values()->all();
    }

    private function pointInPolygon(array $point, array $polygon): bool
    {
        $vertices = count($polygon);
        if ($vertices < 3) return false;
        $inside = false;
        $j = $vertices - 1;
        for ($i = 0; $i < $vertices; $i++) {
            $xi = $polygon[$i]['lng']; $yi = $polygon[$i]['lat'];
            $xj = $polygon[$j]['lng']; $yj = $polygon[$j]['lat'];
            $intersects = (($yi > $point['lat']) !== ($yj > $point['lat']))
                && ($point['lng'] < ($xj - $xi) * ($point['lat'] - $yi) / (($yj - $yi) ?: 0.0000001) + $xi);
            if ($intersects) $inside = !$inside;
            $j = $i;
        }
        return $inside;
    }

    private function timeInRange(Carbon $dateTime, $start, $end): bool
    {
        if (!$start || !$end) return false;
        $time = $dateTime->format('H:i:s');
        $start = (string) $start; $end = (string) $end;
        if ($start <= $end) return $time >= $start && $time <= $end;
        return $time >= $start || $time <= $end;
    }
}
