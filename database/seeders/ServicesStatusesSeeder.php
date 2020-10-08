<?php

namespace Database\Seeders;

use App\Models\ServiceStatus;
use Illuminate\Database\Seeder;

class ServicesStatusesSeeder extends Seeder
{
    public const STATUSES_PRESET = [
        [
            'status_name' => 'Доступно'
        ],
        [
            'status_name' => 'Недоступно'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceStatus::insert(self::STATUSES_PRESET);
    }
}
