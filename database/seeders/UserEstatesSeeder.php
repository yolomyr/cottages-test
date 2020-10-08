<?php

namespace Database\Seeders;

use App\Models\UserEstateType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserEstatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        foreach (UserEstateType::TYPES as $type) {
            DB::table('user_estate_types')->insert([
                'estate_type' => $type
            ]);
        }
    }
}
