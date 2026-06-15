<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder de tarifas AVILL.
 *
 * Las tarifas NO se cargan automáticamente — deben ingresarse manualmente
 * desde el panel de administración en: Admin → AVILL → Tarifas Quibdó
 *
 * Para cada par de zonas (origen → destino) configurar:
 *   - service_type: taxi_urbano | domicilio | mudanza | motocarro | rapimoto_mototaxi
 *   - vehicle_mode: carro | moto | motocarro | mudanza
 *   - pricing_mode: tarifa_fija | cotizacion_manual
 *   - base_amount (COP)
 *   - night_surcharge_amount, holiday_surcharge_amount, rain_surcharge_amount (opcionales)
 */
class AvillManualFareSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('AvillManualFareSeeder: sin tarifas precargadas.');
        $this->command->info('Ingresar tarifas reales desde Admin → AVILL → Tarifas Quibdó.');
    }
}
