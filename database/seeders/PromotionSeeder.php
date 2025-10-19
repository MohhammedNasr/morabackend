<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run()
    {
        $promotions = [
            [
                'code' => 'SUMMER21',
                'description' => 'Summer Sale 2021',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'minimum_order_amount' => 100.00,
                'maximum_discount_amount' => 50.00,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'WINTER21',
                'description' => 'Winter Sale 2021',
                'discount_type' => 'fixed',
                'discount_value' => 15.00,
                'minimum_order_amount' => 50.00,
                'maximum_discount_amount' => null,
                'start_date' => now(),
                'end_date' => now()->addDays(30),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            // Add more promotions as needed
        ];

        foreach ($promotions as $promotion) {
            Promotion::updateOrInsert(['code' => $promotion['code']], $promotion);
        }
    }
}
