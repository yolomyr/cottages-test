<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        foreach (User::ROLES as $role_key => $role) {
            DB::table('user_roles')->insert([
                'role' => $role_key
            ]);
        }
    }
}
