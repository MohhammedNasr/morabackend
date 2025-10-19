<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'sku' => 'PROD-' . $this->faker->unique()->bothify('#####'),
            'name_en' => $this->faker->word,
            'name_ar' => $this->faker->word,
            'description_en' => $this->faker->sentence,
            'description_ar' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 1, 100),
            // 'price' => $this->faker->randomFloat(2, 1, 100),
            'available_quantity' => $this->faker->numberBetween(1, 100),
            'status' => 'active',
            'category_id' => \App\Models\Category::factory(),
            'supplier_id' => \App\Models\Supplier::factory(),
            'price_before' => $this->faker->randomFloat(2, 1, 100),
            'has_discount' => $this->faker->boolean,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
