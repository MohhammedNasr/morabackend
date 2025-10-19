<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('slug', 'admin')->first();

        User::create([
            'name' => 'Admin',
            'email' => 'admin@mora.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'role_id' => $adminRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
