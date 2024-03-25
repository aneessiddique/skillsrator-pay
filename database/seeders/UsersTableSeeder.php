<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@ec.com.pk',
                'password'       => '$2y$10$OqjdGeRIEyv86oNhcCT7X.HekvePa/oN1YFNCttk8Q5n4A0oyLx0e', //admin123
                'remember_token' => null,
            ],
            [
                'id'             => 2,
                'name'           => 'Accountant',
                'email'          => 'accounts@ec.com.pk',
                'password'       => '$2y$10$OqjdGeRIEyv86oNhcCT7X.HekvePa/oN1YFNCttk8Q5n4A0oyLx0e', //admin123
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
