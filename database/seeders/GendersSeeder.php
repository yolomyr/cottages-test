<?php

namespace Database\Seeders;

use App\Models\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        foreach (Gender::GENDERS as $gender) {
            DB::table('genders')->insert([
                'gender_name' => $gender
            ]);
        }
    }
}
