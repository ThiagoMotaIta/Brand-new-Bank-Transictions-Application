<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User 1
        User::create([
            'account_type' => 'C',
            'name' => 'Thiago Mota',
            'email' => 'thiagomotaita1@gmail.com',
            'password' => bcrypt('qwer1234'),
        ]);

        // User 2
        User::create([
            'account_type' => 'C',
            'name' => 'Goku',
            'email' => 'goku@email.com',
            'password' => bcrypt('654321'),
        ]);

        // User 3
        User::create([
            'account_type' => 'C',
            'name' => 'Vegeta',
            'email' => 'vegeta@email.com',
            'password' => bcrypt('654321'),
        ]);

        // Admin User
        User::create([
            'account_type' => 'A',
            'name' => 'Augusto Arnold',
            'email' => 'augusto@turno.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
