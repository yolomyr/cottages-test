<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public const SERVICES_PRESET = [
        [
            'title' => 'Бассейн',
            'status_id' => 1,
            'logo' => 'services/1/service_1.jpg'
        ],
        [
            'title' => 'Тенистый корт',
            'status_id' => 1,
            'logo' => 'services/2/service_2.jpg'
        ],
        [
            'title' => 'Бильярд',
            'status_id' => 1,
            'logo' => 'services/3/service_3.jpg'
        ],
        [
            'title' => 'Кафе',
            'status_id' => 1,
            'logo' => 'services/4/service_4.jpg'
        ]
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Service::insert(self::SERVICES_PRESET);
    }
}
