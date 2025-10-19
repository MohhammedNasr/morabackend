<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name_en' => 'Fresh Produce',
                'name_ar' => 'منتجات طازجة',
                'slug' => 'fresh-produce',
                'description_en' => 'Fresh fruits and vegetables',
                'description_ar' => 'فواكه وخضروات طازجة',
                'image' => 'categories/fresh-produce.jpg',
                'status' => 'active',
            ],
            [
                'name_en' => 'Dairy & Eggs',
                'name_ar' => 'منتجات الألبان والبيض',
                'slug' => 'dairy-eggs',
                'description_en' => 'Milk, cheese, yogurt, and eggs',
                'description_ar' => 'حليب، جبن، زبادي، وبيض',
                'image' => 'categories/dairy-eggs.jpg',
                'status' => 'active',
            ],
            [
                'name_en' => 'Meat & Poultry',
                'name_ar' => 'لحوم ودواجن',
                'slug' => 'meat-poultry',
                'description_en' => 'Fresh meat and poultry products',
                'description_ar' => 'منتجات اللحوم والدواجن الطازجة',
                'image' => 'categories/meat-poultry.jpg',
                'status' => 'active',
            ],
            [
                'name_en' => 'Bakery',
                'name_ar' => 'مخبوزات',
                'slug' => 'bakery',
                'description_en' => 'Fresh bread and baked goods',
                'description_ar' => 'خبز طازج ومخبوزات',
                'image' => 'categories/bakery.jpg',
                'status' => 'active',
            ],
            [
                'name_en' => 'Beverages',
                'name_ar' => 'مشروبات',
                'slug' => 'beverages',
                'description_en' => 'Soft drinks, juices, and water',
                'description_ar' => 'مشروبات غازية، عصائر، ومياه',
                'image' => 'categories/beverages.jpg',
                'status' => 'active',
            ],
            [
                'name_en' => 'Snacks',
                'name_ar' => 'وجبات خفيفة',
                'slug' => 'snacks',
                'description_en' => 'Chips, nuts, and other snacks',
                'description_ar' => 'شيبس، مكسرات، ووجبات خفيفة أخرى',
                'image' => 'categories/snacks.jpg',
                'status' => 'active',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrInsert(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
