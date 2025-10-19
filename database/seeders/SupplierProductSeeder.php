<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 suppliers
        $suppliers = Supplier::factory()
            ->count(10)
            ->create();

        // Create 20 products and assign to random suppliers
        Product::factory()
            ->count(20)
            ->create()
            ->each(function ($product) use ($suppliers) {
                $product->supplier_id = $suppliers->random()->id;
                $product->save();
            });
    }
}
