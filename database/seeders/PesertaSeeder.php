<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Peserta;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // insert
        // Peserta::create([
        //     'name' => 'Sheehan',
        //     'email' => 'sheehan@gmail.com',
        //     'age' => 27,
        //     'address' => 'bekasi',
        // ]);
        Peserta::factory(50)->create();
    }
}
