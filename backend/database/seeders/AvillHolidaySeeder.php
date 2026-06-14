<?php

namespace Database\Seeders;

use App\Models\AvillHoliday;
use Illuminate\Database\Seeder;

class AvillHolidaySeeder extends Seeder
{
    public function run()
    {
        $holidays = [
            // 2025
            ['date' => '2025-01-01', 'name' => 'Año Nuevo'],
            ['date' => '2025-01-06', 'name' => 'Día de Reyes (trasladado)'],
            ['date' => '2025-03-24', 'name' => 'Día de San José (trasladado)'],
            ['date' => '2025-04-13', 'name' => 'Domingo de Ramos'],
            ['date' => '2025-04-17', 'name' => 'Jueves Santo'],
            ['date' => '2025-04-18', 'name' => 'Viernes Santo'],
            ['date' => '2025-04-20', 'name' => 'Domingo de Resurrección'],
            ['date' => '2025-05-01', 'name' => 'Día del Trabajo'],
            ['date' => '2025-06-02', 'name' => 'Ascensión del Señor (trasladado)'],
            ['date' => '2025-06-23', 'name' => 'Corpus Christi (trasladado)'],
            ['date' => '2025-06-30', 'name' => 'Sagrado Corazón (trasladado)'],
            ['date' => '2025-07-07', 'name' => 'San Pedro y San Pablo (trasladado)'],
            ['date' => '2025-07-20', 'name' => 'Día de la Independencia'],
            ['date' => '2025-08-07', 'name' => 'Batalla de Boyacá'],
            ['date' => '2025-08-18', 'name' => 'La Asunción de la Virgen (trasladado)'],
            ['date' => '2025-10-13', 'name' => 'Día de la Raza (trasladado)'],
            ['date' => '2025-11-03', 'name' => 'Todos los Santos (trasladado)'],
            ['date' => '2025-11-17', 'name' => 'Independencia de Cartagena (trasladado)'],
            ['date' => '2025-12-08', 'name' => 'Día de la Inmaculada Concepción'],
            ['date' => '2025-12-25', 'name' => 'Navidad'],
            // 2026
            ['date' => '2026-01-01', 'name' => 'Año Nuevo'],
            ['date' => '2026-01-12', 'name' => 'Día de Reyes (trasladado)'],
            ['date' => '2026-03-23', 'name' => 'Día de San José (trasladado)'],
            ['date' => '2026-04-02', 'name' => 'Jueves Santo'],
            ['date' => '2026-04-03', 'name' => 'Viernes Santo'],
            ['date' => '2026-05-01', 'name' => 'Día del Trabajo'],
            ['date' => '2026-05-18', 'name' => 'Ascensión del Señor (trasladado)'],
            ['date' => '2026-06-08', 'name' => 'Corpus Christi (trasladado)'],
            ['date' => '2026-06-15', 'name' => 'Sagrado Corazón (trasladado)'],
            ['date' => '2026-06-29', 'name' => 'San Pedro y San Pablo'],
            ['date' => '2026-07-20', 'name' => 'Día de la Independencia'],
            ['date' => '2026-08-07', 'name' => 'Batalla de Boyacá'],
            ['date' => '2026-08-17', 'name' => 'La Asunción de la Virgen (trasladado)'],
            ['date' => '2026-10-12', 'name' => 'Día de la Raza'],
            ['date' => '2026-11-02', 'name' => 'Todos los Santos (trasladado)'],
            ['date' => '2026-11-16', 'name' => 'Independencia de Cartagena (trasladado)'],
            ['date' => '2026-12-08', 'name' => 'Día de la Inmaculada Concepción'],
            ['date' => '2026-12-25', 'name' => 'Navidad'],
        ];

        foreach ($holidays as $holiday) {
            AvillHoliday::firstOrCreate(
                ['date' => $holiday['date']],
                array_merge($holiday, [
                    'country_code' => 'CO',
                    'applies_to'   => 'todos',
                    'is_active'    => true,
                ])
            );
        }
    }
}
