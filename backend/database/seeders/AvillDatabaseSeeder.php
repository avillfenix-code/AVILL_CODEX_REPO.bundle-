<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Ejecutar con: php artisan db:seed --class=AvillDatabaseSeeder
 *
 * Orden importante: áreas primero, luego tarifas (FK), luego recargos y festivos.
 */
class AvillDatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AvillPermissionsSeeder::class,
            AvillServiceAreaSeeder::class,
            AvillSurchargeSeeder::class,
            AvillHolidaySeeder::class,
            AvillManualFareSeeder::class,
        ]);
    }
}
