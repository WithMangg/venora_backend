<?php

namespace Database\Seeders;

use App\Models\Roles;
use App\Models\SettingWeb;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => '1'
        ]);

        Roles::create(
            ['role_name' => 'admin',],
            ['role_name' => 'pengguna',],
            ['role_name' => 'dokter',]

        );

        SettingWeb::create([
            'favicon' => 'null',
            'logo' => 'null',
            'description' => 'Ini adalah SIM KLINIK Lite',
            'site_name' => 'Mini Opnicare System',
            'email' => 'rohmanuyeoke@gmail.com',
            'phone' => '6285713050749',
            'address' => 'Pesarean, Pagerbarang, Tegal',
        ]);
    }
}
