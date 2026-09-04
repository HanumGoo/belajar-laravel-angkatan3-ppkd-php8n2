<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'role_id' => 1,
            'email' => 'admin@gmail.com',
            'password' => '12345678'
        ]);
        User::create([
            'name' => 'Cashier',
            'role_id' => 2,
            'email' => 'cashier@gmail.com',
            'password' => '12345678'
        ]);
        User::create([
            'name' => 'Pimpinan',
            'role_id' => 3,
            'email' => 'pimpinan@gmail.com',
            'password' => '12345678'
        ]);
    }
}
