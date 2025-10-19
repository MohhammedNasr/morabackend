<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name_en' => $name = $this->faker->unique()->word,
            'name_ar' => $this->faker->word,
            'slug' => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'image' => $this->faker->imageUrl(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
