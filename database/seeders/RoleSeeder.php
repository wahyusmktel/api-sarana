<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['id' => Str::uuid(), 'name' => 'admin', 'description' => 'Administrator'],
            ['id' => Str::uuid(), 'name' => 'operator', 'description' => 'Petugas input data'],
            ['id' => Str::uuid(), 'name' => 'kepala_sarpras', 'description' => 'Penanggung jawab Sarpras'],
        ]);
    }
}
