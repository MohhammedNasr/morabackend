<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            [
                'name' => 'Store Owner',
                'slug' => 'store-owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Representative',
                'slug' => 'representative',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Supplier',
                'slug' => 'supplier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                $role
            );
        } // Added missing closing brace
    } // Added missing closing brace for the run method
} // Added missing closing brace for the RoleSeeder class
