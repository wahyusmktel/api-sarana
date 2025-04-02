<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\AssetCondition;
use App\Models\AssetLocation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = AssetCategory::where('name', 'Peralatan')->first();
        $tipe = AssetType::where('name', 'Laptop')->first();
        $kondisi = AssetCondition::where('name', 'Baik')->first();
        $lokasi = AssetLocation::where('name', 'Lab Komputer')->first();

        Asset::create([
            'id' => Str::uuid(),
            'asset_code' => 'AST-0001',
            'name' => 'Laptop Guru',
            'serial_number' => 'SN-ABC12345',
            'category_id' => $kategori->id,
            'type_id' => $tipe->id,
            'condition_id' => $kondisi->id,
            'location_id' => $lokasi->id,
            'acquisition_date' => now()->subYears(1),
            'acquisition_cost' => 7500000,
            'funding_source' => 'BOS',
            'is_active' => true,
            'qr_code_path' => null,
            'responsible_user_id' => User::first()->id,
        ]);
    }
}
