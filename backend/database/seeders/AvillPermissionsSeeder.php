<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * Ejecutar con: php artisan db:seed --class=AvillPermissionsSeeder
 *
 * Agrega permisos AVILL al rol 'admin' sin modificar el seeder original de Glover.
 * Seguro para ejecutar múltiples veces (firstOrCreate).
 */
class AvillPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permisos = [
            'view-avill-fares',
            'manage-avill-fares',
            'view-avill-areas',
            'manage-avill-areas',
            'view-avill-surcharges',
            'manage-avill-surcharges',
            'view-avill-holidays',
            'manage-avill-holidays',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso, 'guard_name' => 'web']);
        }

        // Asignar todos los permisos AVILL al rol admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permisos);
        }

        // También asignar permisos de lectura al city-admin
        $cityAdminRole = Role::where('name', 'city-admin')->first();
        if ($cityAdminRole) {
            $cityAdminRole->givePermissionTo([
                'view-avill-fares',
                'view-avill-areas',
                'view-avill-surcharges',
                'view-avill-holidays',
            ]);
        }
    }
}
