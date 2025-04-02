<?php

namespace Database\Seeders;

use App\Models\AssetLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetLocationSeeder extends Seeder
{
    public function run(): void
    {
        AssetLocation::insert([
            ['id' => Str::uuid(), 'name' => 'Lab Komputer', 'room_code' => 'LAB01', 'building_name' => 'Gedung A'],
            ['id' => Str::uuid(), 'name' => 'Ruang Guru', 'room_code' => 'RG01', 'building_name' => 'Gedung B'],
            ['id' => Str::uuid(), 'name' => 'Aula', 'room_code' => 'AU01', 'building_name' => 'Gedung Utama'],
        ]);
    }
}
