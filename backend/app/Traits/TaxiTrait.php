<?php

namespace App\Traits;

use App\Models\VehicleType;
use App\Services\AvillFareService;
use App\Services\DriverLocationService;
use Illuminate\Support\Facades\Http;

trait TaxiTrait
{
    use GoogleMapApiTrait;

    public function getTaxiOrderTotalPrice($vehicleType, $pickup, $dropoff)
    {
        try {
            $avillQuote = app(AvillFareService::class)->quoteForTrip(
                $vehicleType, $pickup, $dropoff, null,
                (bool) request()->input('is_raining', false)
            );
        } catch (\Throwable $e) {
            logger()->error('AvillFareService error in getTaxiOrderTotalPrice: ' . $e->getMessage());
            $avillQuote = null;
        }

        if ($avillQuote && !($avillQuote['pending'] ?? false)) {
            return $avillQuote['total'];
        }

        $distance = round($this->getRelativeDistance($pickup, $dropoff), 2);
        $drivingSpeed = setting("taxi.drivingSpeed", 50);
        $drivingTime = ceil(($distance / $drivingSpeed) * 60);
        $totalTripFare = $vehicleType->base_fare + ($vehicleType->time_fare * $drivingTime) + ($distance * $vehicleType->distance_fare);
        return $totalTripFare < $vehicleType->min_fare ? $vehicleType->min_fare : $totalTripFare;
    }

    public function getRecalculatedTaxiOrderTotalPrice($order)
    {
        if (!((bool) setting('taxi.recalculateFare', false))) {
            return $order->total;
        }
        $pickup = $order->taxi_order->pickup_latitude . "," . $order->taxi_order->pickup_longitude;
        $dropoff = request()->latlng ?? ($order->taxi_order->dropoff_latitude . "," . $order->taxi_order->dropoff_longitude);

        $vehicleType = VehicleType::find($order->taxi_order->vehicle_type_id);
        try {
            $avillQuote = app(AvillFareService::class)->quoteForTrip($vehicleType, $pickup, $dropoff, null, (bool) request()->input('is_raining', false));
        } catch (\Throwable $e) {
            logger()->error('AvillFareService error in getRecalculatedTaxiOrderTotalPrice: ' . $e->getMessage());
            $avillQuote = null;
        }
        if ($avillQuote && !($avillQuote['pending'] ?? false)) { return $avillQuote['total']; }

        $distance = round($this->getRelativeDistance($pickup, $dropoff), 2);
        $drivingSpeed = setting("taxi.drivingSpeed", 50);
        $orderEnrouteStatusModel = $order->latestStatus('enroute');
        $drivingTime = $orderEnrouteStatusModel
            ? ceil(now()->diffInMinutes($orderEnrouteStatusModel->created_at))
            : ceil(now()->diffInMinutes($order->getOriginal('updated_at')));
        $totalTripFare = $vehicleType->base_fare + ($vehicleType->time_fare * $drivingTime) + ($distance * $vehicleType->distance_fare);
        return $totalTripFare < $vehicleType->min_fare ? $vehicleType->min_fare : $totalTripFare;
    }

    public function getFareBreakdown($vehicleType, $pickup, $dropoff)
    {
        try {
            $avillQuote = app(AvillFareService::class)->quoteForTrip($vehicleType, $pickup, $dropoff, null, (bool) request()->input('is_raining', false));
        } catch (\Throwable $e) {
            logger()->error('AvillFareService error in getFareBreakdown: ' . $e->getMessage());
            $avillQuote = null;
        }

        if ($avillQuote) {
            $vehicleType->avill_fixed_fare = (bool) ($avillQuote['avill_fixed_fare'] ?? false);
            $vehicleType->avill_fare_pending = (bool) ($avillQuote['pending'] ?? false);
            $vehicleType->avill_fare_message = $avillQuote['message'] ?? null;
            $vehicleType->avill_origin_area = $avillQuote['origin_area'];
            $vehicleType->avill_destination_area = $avillQuote['destination_area'];
            $vehicleType->avill_base_amount = $avillQuote['base_amount'];
            $vehicleType->avill_management_surcharge_amount = $avillQuote['management_surcharge_amount'] ?? 0;
            $vehicleType->avill_surcharge_total = $avillQuote['surcharge_total'];
            $vehicleType->avill_total = $avillQuote['total'];
            $vehicleType->avill_surcharges = $avillQuote['surcharges'];
            $vehicleType->trip_distance = 0;
            $vehicleType->trip_time = 0;
            return $vehicleType;
        }

        $distance = round($this->getRelativeDistance($pickup, $dropoff), 2);
        $drivingSpeed = setting("taxi.drivingSpeed", 50);
        $drivingTime = ceil(($distance / $drivingSpeed) * 60);
        $vehicleType->trip_distance = $distance;
        $vehicleType->trip_time = $drivingTime;
        return $vehicleType;
    }

    public function getRecalculatedTaxiOrderBreakdown($order)
    {
        $taxiOrder = $order->taxi_order;
        $pickup = $taxiOrder->pickup_latitude . "," . $taxiOrder->pickup_longitude;
        $dropoff = $taxiOrder->dropoff_latitude . "," . $taxiOrder->dropoff_longitude;
        $vehicleType = VehicleType::find($taxiOrder->vehicle_type_id);
        try {
            $avillQuote = app(AvillFareService::class)->quoteForTrip($vehicleType, $pickup, $dropoff, null, (bool) request()->input('is_raining', false));
        } catch (\Throwable $e) {
            logger()->error('AvillFareService error in getRecalculatedTaxiOrderBreakdown: ' . $e->getMessage());
            $avillQuote = null;
        }

        if ($avillQuote) { $taxiOrder->trip_distance = 0; $taxiOrder->trip_time = 0; return $taxiOrder; }

        $distance = round($this->getRelativeDistance($pickup, $dropoff), 2);
        $orderEnrouteStatusModel = $order->latestStatus('enroute');
        $drivingTime = $orderEnrouteStatusModel
            ? ceil(now()->diffInMinutes($orderEnrouteStatusModel->created_at))
            : ceil(($distance / setting("taxi.drivingSpeed", 50)) * 60);
        $taxiOrder->trip_distance = $distance;
        $taxiOrder->trip_time = $drivingTime;
        return $taxiOrder;
    }

    public function includeNearestDriverEta(&$vehicleType, $pickupLatLng)
    {
        if (!isUsingWebsocket()) return;
        $lat1 = (float) explode(",", $pickupLatLng)[0];
        $lon1 = (float) explode(",", $pickupLatLng)[1];
        $maxDriverOrderNotificationAtOnce = (int) setting('maxDriverOrderNotificationAtOnce', 1);
        $driverDocuments = (new DriverLocationService())->getNearbyTaxiDrivers($lat1, $lon1, driverSearchRadius(null), $maxDriverOrderNotificationAtOnce, $vehicleType->id);
        $nearestDriverData = collect($driverDocuments)->sortBy('distance')->first();
        if ($nearestDriverData != null) {
            $etaInMinutes = (int) max(5, round(($nearestDriverData["distance"] ?? 100) / setting('taxi.drivingSpeed', 50) * 60));
            $vehicleType->nearest_driver_in_minutes = $etaInMinutes;
        }
    }
}
