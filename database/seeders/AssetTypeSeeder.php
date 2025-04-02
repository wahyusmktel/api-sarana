<?php

namespace Database\Seeders;

use App\Models\AssetType;
use App\Models\AssetCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $peralatanId = AssetCategory::where('name', 'Peralatan')->value('id');

        AssetType::insert([
            ['id' => Str::uuid(), 'name' => 'Laptop', 'category_id' => $peralatanId],
            ['id' => Str::uuid(), 'name' => 'Proyektor', 'category_id' => $peralatanId],
            ['id' => Str::uuid(), 'name' => 'Meja Guru', 'category_id' => $peralatanId],
        ]);
    }
}
