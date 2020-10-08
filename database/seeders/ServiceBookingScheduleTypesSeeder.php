<?php

namespace Database\Seeders;

use App\Models\ServiceBookingScheduleType;
use Illuminate\Database\Seeder;

class ServiceBookingScheduleTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (ServiceBookingScheduleType::TYPES as $name => $type) {
            ServiceBookingScheduleType::create([
                'schedule_type_name' => $name
            ]);
        }
    }
}
