<?php

namespace Database\Seeders;

use App\Models\AvillServiceArea;
use Illuminate\Database\Seeder;

/**
 * Zonas principales de Quibdó, Chocó, Colombia.
 * Sin polígonos GeoJSON — agregar desde el admin panel (AVILL → Zonas)
 * una vez que se tenga el mapa de la ciudad.
 */
class AvillServiceAreaSeeder extends Seeder
{
    public function run()
    {
        $zonas = [
            // Barrios centrales
            ['name' => 'Centro',              'type' => 'barrio'],
            ['name' => 'El Jardín',           'type' => 'barrio'],
            ['name' => 'La Yesquita',         'type' => 'barrio'],
            ['name' => 'Niño Jesús',          'type' => 'barrio'],
            ['name' => 'El Reposo',           'type' => 'barrio'],
            ['name' => 'La Troje',            'type' => 'barrio'],
            ['name' => 'Obrero',              'type' => 'barrio'],
            ['name' => 'Cristo Rey',          'type' => 'barrio'],
            ['name' => 'Carrera Primera',     'type' => 'barrio'],
            // Barrios periféricos
            ['name' => 'La Playita',          'type' => 'barrio'],
            ['name' => 'Guadalupe',           'type' => 'barrio'],
            ['name' => 'La Esmeralda',        'type' => 'barrio'],
            ['name' => 'Urbanización Aurora', 'type' => 'barrio'],
            ['name' => 'Villa del Río',       'type' => 'barrio'],
            ['name' => 'Medrano',             'type' => 'barrio'],
            ['name' => 'Alameda Reyes',       'type' => 'barrio'],
            ['name' => 'Campo Alegre',        'type' => 'barrio'],
            ['name' => 'La Paz',              'type' => 'barrio'],
            ['name' => 'San Vicente',         'type' => 'barrio'],
            ['name' => 'Cascajal',            'type' => 'barrio'],
            // Zonas especiales
            ['name' => 'Aeropuerto El Caraño','type' => 'zona'],
            ['name' => 'Terminal de Transporte','type' => 'zona'],
            ['name' => 'Hospital San Francisco','type' => 'zona'],
            ['name' => 'Universidad Tecnológica del Chocó','type' => 'zona'],
            ['name' => 'Mercado Central',     'type' => 'zona'],
            // Zona rural / salida
            ['name' => 'Salida a Medellín (Tutunendo)', 'type' => 'zona'],
            ['name' => 'Salida a Istmina',    'type' => 'zona'],
        ];

        foreach ($zonas as $zona) {
            AvillServiceArea::firstOrCreate(
                ['name' => $zona['name']],
                array_merge($zona, [
                    'city'         => 'Quibdó',
                    'department'   => 'Chocó',
                    'country_code' => 'CO',
                    'is_active'    => true,
                ])
            );
        }
    }
}
