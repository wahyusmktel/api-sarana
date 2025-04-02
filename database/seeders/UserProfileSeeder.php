<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('username', 'admin')->first();

        if ($admin) {
            UserProfile::create([
                'id' => Str::uuid(),
                'user_id' => $admin->id,
                'full_name' => 'Administrator Utama',
                'gender' => 'Laki-laki',
                'birth_date' => '1990-01-01',
                'phone' => '081234567890',
                'address' => 'Kantor Pusat Sarpras',
                'avatar_url' => null,
                'bio' => 'Akun utama sistem.',
                'status' => true,
            ]);
        }
    }
}
