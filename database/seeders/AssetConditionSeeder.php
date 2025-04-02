<?php

namespace Database\Seeders;

use App\Models\AssetCondition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetConditionSeeder extends Seeder
{
    public function run(): void
    {
        AssetCondition::insert([
            ['id' => Str::uuid(), 'name' => 'Baik', 'description' => 'Kondisi normal, siap digunakan'],
            ['id' => Str::uuid(), 'name' => 'Rusak Ringan', 'description' => 'Butuh perbaikan kecil'],
            ['id' => Str::uuid(), 'name' => 'Rusak Berat', 'description' => 'Tidak dapat digunakan'],
        ]);
    }
}
