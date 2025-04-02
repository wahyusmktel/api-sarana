<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     $this->call([
    //         RoleSeeder::class,
    //         UserSeeder::class,
    //         AssetCategorySeeder::class,
    //         AssetConditionSeeder::class,
    //         AssetLocationSeeder::class,
    //     ]);
    // }

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            UserProfileSeeder::class,
            AssetCategorySeeder::class,
            AssetConditionSeeder::class,
            AssetLocationSeeder::class,
            AssetTypeSeeder::class,
            AssetSeeder::class,
        ]);
    }
}
