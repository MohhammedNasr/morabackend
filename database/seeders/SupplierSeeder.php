<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $supplierRole = Role::where('slug', 'supplier')->first();

        // Create supplier user
        $user = User::updateOrInsert(
            ['email' => 'supplier@test.com'],
            [
                'name' => 'Test Supplier',
                'password' => bcrypt('password'),
                'phone' => '+966500000000',
                'role_id' => $supplierRole->id,
            ]
        );

        // Create supplier if user was created or found
        $userInstance = User::where('email', 'supplier@test.com')->first();

        if ($userInstance) {
            Supplier::updateOrCreate(
                ['user_id' => $userInstance->id],
                [
                    'name' => 'Test Supplier Company',
                    'contact_name' => 'Supplier Contact',
                    'email' => 'supplier@example.com',
                    'phone' => '1234567890',
                    'address' => 'Supplier Address',
                    'commercial_record' => '1234567891',
                    'tax_id' => '1234567890',
                    'payment_term_days' => 30,
                    'is_active' => true,
                ]
            );
        }
    }
}
