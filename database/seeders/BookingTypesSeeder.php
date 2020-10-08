<?php

namespace Database\Seeders;

use App\Models\BookingType;
use Illuminate\Database\Seeder;

class BookingTypesSeeder extends Seeder
{
    public const TYPES_PRESET = [
        1 => 'Свободная',
        2 => 'Семейная'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::TYPES_PRESET as $type) {
            BookingType::create([
                'booking_type_name' => $type
            ]);
        }
    }
}
