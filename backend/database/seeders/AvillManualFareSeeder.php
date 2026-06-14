<?php

namespace Database\Seeders;

use App\Models\AvillManualFare;
use App\Models\AvillServiceArea;
use Illuminate\Database\Seeder;

/**
 * Tarifas de ejemplo para Quibdó.
 * Ajustar los montos reales en Admin → AVILL → Tarifas antes de salir a producción.
 */
class AvillManualFareSeeder extends Seeder
{
    public function run()
    {
        // Zonas clave para cruzar
        $zonas = [
            'Centro'                => AvillServiceArea::where('name', 'Centro')->value('id'),
            'El Jardín'             => AvillServiceArea::where('name', 'El Jardín')->value('id'),
            'La Yesquita'           => AvillServiceArea::where('name', 'La Yesquita')->value('id'),
            'Aeropuerto El Caraño'  => AvillServiceArea::where('name', 'Aeropuerto El Caraño')->value('id'),
            'Terminal de Transporte'=> AvillServiceArea::where('name', 'Terminal de Transporte')->value('id'),
            'Hospital San Francisco'=> AvillServiceArea::where('name', 'Hospital San Francisco')->value('id'),
            'Mercado Central'       => AvillServiceArea::where('name', 'Mercado Central')->value('id'),
        ];

        // Eliminar zonas no encontradas (por si el seeder de áreas no corrió antes)
        $zonas = array_filter($zonas);
        if (count($zonas) < 2) {
            $this->command->warn('Ejecutar AvillServiceAreaSeeder primero.');
            return;
        }

        // Tarifas taxi urbano (carro) — origen → destino
        $tarifasTaxi = [
            // [origen_key, destino_key, tarifa_base, minimo, gestion, noche, festivo]
            ['Centro',               'Aeropuerto El Caraño',   12000, 10000, 0, 2000, 3000],
            ['Centro',               'Terminal de Transporte',  8000,  7000, 0, 2000, 3000],
            ['Centro',               'Hospital San Francisco',  7000,  6000, 0, 2000, 3000],
            ['Centro',               'El Jardín',               6000,  5000, 0, 2000, 3000],
            ['Centro',               'La Yesquita',             5000,  5000, 0, 2000, 3000],
            ['Centro',               'Mercado Central',         4000,  4000, 0, 2000, 3000],
            ['El Jardín',            'Aeropuerto El Caraño',   10000,  9000, 0, 2000, 3000],
            ['El Jardín',            'Hospital San Francisco',  8000,  7000, 0, 2000, 3000],
            ['La Yesquita',          'Aeropuerto El Caraño',   11000, 10000, 0, 2000, 3000],
            ['Aeropuerto El Caraño', 'Terminal de Transporte', 15000, 13000, 0, 2000, 3000],
        ];

        // Tarifas domicilio (moto/repartidor)
        $tarifasDomicilio = [
            // [origen_key, destino_key, tarifa_base, minimo, gestion]
            ['Centro',      'Centro',               4000, 3000, 1000],
            ['Centro',      'El Jardín',            5000, 4000, 1000],
            ['Centro',      'La Yesquita',          5000, 4000, 1000],
            ['Centro',      'Hospital San Francisco',6000, 5000, 1500],
            ['Centro',      'Mercado Central',       4000, 3000, 1000],
            ['El Jardín',   'Centro',               5000, 4000, 1000],
            ['La Yesquita', 'Centro',               5000, 4000, 1000],
        ];

        foreach ($tarifasTaxi as [$origenKey, $destinoKey, $base, $minimo, $gestion, $noche, $festivo]) {
            if (!isset($zonas[$origenKey], $zonas[$destinoKey])) continue;
            AvillManualFare::firstOrCreate(
                [
                    'origin_area_id'      => $zonas[$origenKey],
                    'destination_area_id' => $zonas[$destinoKey],
                    'service_type'        => 'taxi_urbano',
                    'vehicle_mode'        => 'carro',
                ],
                [
                    'pricing_mode'                => AvillManualFare::PRICING_MODE_FIXED,
                    'base_amount'                 => $base,
                    'minimum_amount'              => $minimo,
                    'management_surcharge_amount' => $gestion,
                    'night_surcharge_amount'      => $noche,
                    'holiday_surcharge_amount'    => $festivo,
                    'rain_surcharge_amount'       => 0,
                    'additional_km_amount'        => 0,
                    'is_active'                   => true,
                ]
            );
        }

        foreach ($tarifasDomicilio as [$origenKey, $destinoKey, $base, $minimo, $gestion]) {
            if (!isset($zonas[$origenKey], $zonas[$destinoKey])) continue;
            AvillManualFare::firstOrCreate(
                [
                    'origin_area_id'      => $zonas[$origenKey],
                    'destination_area_id' => $zonas[$destinoKey],
                    'service_type'        => 'domicilio',
                    'vehicle_mode'        => 'repartidor',
                ],
                [
                    'pricing_mode'                => AvillManualFare::PRICING_MODE_FIXED,
                    'base_amount'                 => $base,
                    'minimum_amount'              => $minimo,
                    'management_surcharge_amount' => $gestion,
                    'night_surcharge_amount'      => 0,
                    'holiday_surcharge_amount'    => 0,
                    'rain_surcharge_amount'       => 0,
                    'additional_km_amount'        => 0,
                    'is_active'                   => true,
                ]
            );
        }
    }
}
