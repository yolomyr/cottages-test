<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    final public function run(): void
    {
        $this->call([
            GendersSeeder::class,
            UserEstatesSeeder::class,
            UserRolesSeeder::class,
            UsersSeeder::class,

            ServicesStatusesSeeder::class,
            ServicesSeeder::class,
            BookingTypesSeeder::class,
            ServiceBookingScheduleTypesSeeder::class,
            ServiceBookingsSeeder::class
        ]);
    }
}
