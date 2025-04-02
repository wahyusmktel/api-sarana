<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = Str::uuid();

        User::create([
            'id' => $adminId,
            'username' => 'admin',
            'email' => 'admin@sarpras.id',
            'password' => Hash::make('password'),
            'status' => true,
        ]);

        // Tambahkan role ke user
        DB::table('role_user')->insert([
            'user_id' => $adminId,
            'role_id' => DB::table('roles')->where('name', 'admin')->value('id'),
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
