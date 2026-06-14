<?php

namespace Database\Seeders;

use App\Models\AvillSurcharge;
use Illuminate\Database\Seeder;

class AvillSurchargeSeeder extends Seeder
{
    public function run()
    {
        $recargos = [
            [
                'name'            => 'Recargo nocturno (22:00 - 06:00)',
                'service_type'    => null,
                'vehicle_mode'    => null,
                'amount'          => 2000,
                'applies_night'   => true,
                'applies_sunday'  => false,
                'applies_holiday' => false,
            ],
            [
                'name'            => 'Recargo dominical',
                'service_type'    => null,
                'vehicle_mode'    => null,
                'amount'          => 2000,
                'applies_night'   => false,
                'applies_sunday'  => true,
                'applies_holiday' => false,
            ],
            [
                'name'            => 'Recargo festivo',
                'service_type'    => null,
                'vehicle_mode'    => null,
                'amount'          => 3000,
                'applies_night'   => false,
                'applies_sunday'  => false,
                'applies_holiday' => true,
            ],
            [
                'name'            => 'Recargo nocturno festivo',
                'service_type'    => null,
                'vehicle_mode'    => null,
                'amount'          => 4000,
                'applies_night'   => true,
                'applies_sunday'  => false,
                'applies_holiday' => true,
            ],
        ];

        foreach ($recargos as $recargo) {
            AvillSurcharge::firstOrCreate(
                ['name' => $recargo['name']],
                array_merge($recargo, ['is_active' => true])
            );
        }
    }
}
