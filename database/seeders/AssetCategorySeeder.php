<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        AssetCategory::insert([
            ['id' => Str::uuid(), 'name' => 'Tanah', 'description' => 'Aset berupa tanah'],
            ['id' => Str::uuid(), 'name' => 'Bangunan', 'description' => 'Aset berupa bangunan'],
            ['id' => Str::uuid(), 'name' => 'Peralatan', 'description' => 'Aset berupa peralatan dan perlengkapan'],
        ]);
    }
}
