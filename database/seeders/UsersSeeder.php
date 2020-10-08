<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserEstate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public const USERS_PRESET = [
        [
            'name' => 'Rustem',
            'surname' => 'Gadiev',
            'birthday' => '2018-09-01',
            'gender_id' => 1,
            'phone' => '+78982990781',
            'email' => 'rustem2002@ya.ru',
            'user_role_id' => 1,
        ],
        [
            'name' => 'Vlad',
            'surname' => 'Elif',
            'birthday' => '2020-09-04',
            'gender_id' => 1,
            'phone' => '+79896120193',
            'email' => 'lihobaka@gmail.com',
            'user_role_id' => 1,
        ],
        [
            'name' => 'Дмитрий',
            'surname' => 'Кашуба',
            'birthday' => '2020-09-01',
            'gender_id' => 1,
            'phone' => '+78982990711',
            'email' => 'dkashuba@afonico.ru',
            'user_role_id' => 1,
        ],
    ];

    public const ESTATES_PRESET = [
        'user_id' => 1,
        'estate_type_id' => 1,
        'estate_number' => 1
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    final public function run(): void
    {
        $user_estate = self::ESTATES_PRESET;
        foreach (self::USERS_PRESET as $user_preset) {
            $user_preset['password'] = Hash::make($user_preset['email']);
            $user = User::create($user_preset);

            $user_estate['user_id'] = $user->id;
            UserEstate::create($user_estate);
        }
    }
}
