<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'key' => 'app_name',
            'value' => 'My CMS',
        ]);

        Setting::create([
            'key' => 'app_logo',
            'value' => null,
        ]);

        Setting::create([
            'key' => 'app_favicon',
            'value' => null,
        ]);

        Setting::create([
            'key' => 'app_email',
            'value' => 'admin@example.com',
        ]);

        Setting::create([
            'key' => 'app_phone',
            'value' => '089632788051',
        ]);

        Setting::create([
            'key' => 'app_address',
            'value' => 'Jl. Raya Cibinong No. 1, Bogor, Indonesia',
        ]);
    }
}
