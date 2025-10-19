<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            SettingSeeder::class,
            PromotionSeeder::class,
            CitySeeder::class,
            AreaSeeder::class,
            SupplierProductSeeder::class,
            RejectionReasonsSeeder::class,
            BankSeeder::class
        ]);
    }
}
