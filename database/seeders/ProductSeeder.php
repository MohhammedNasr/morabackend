<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get the supplier created by SupplierSeeder
        $supplier = Supplier::where('email', 'supplier@example.com')->first();
        if (!$supplier) {
            throw new \Exception('Supplier not found. Please run SupplierSeeder first.');
        }

        // Create default categories if they don't exist
        $categories = [
            'fresh-produce' => Category::firstOrCreate(
                ['slug' => 'fresh-produce'],
                [
                    'name_en' => 'Fresh Produce',
                    'name_ar' => 'منتجات طازجة',
                    'description_en' => 'Fresh fruits and vegetables',
                    'description_ar' => 'فواكه وخضروات طازجة'
                ]
            ),
            'dairy-eggs' => Category::firstOrCreate(
                ['slug' => 'dairy-eggs'],
                [
                    'name_en' => 'Dairy & Eggs',
                    'name_ar' => 'ألبان وبيض',
                    'description_en' => 'Milk, cheese, eggs and dairy products',
                    'description_ar' => 'حليب، جبن، بيض ومنتجات الألبان'
                ]
            ),
            'meat-poultry' => Category::firstOrCreate(
                ['slug' => 'meat-poultry'],
                [
                    'name_en' => 'Meat & Poultry',
                    'name_ar' => 'لحوم ودواجن',
                    'description_en' => 'Fresh meat and poultry products',
                    'description_ar' => 'منتجات اللحوم والدواجن الطازجة'
                ]
            ),
            'bakery' => Category::firstOrCreate(
                ['slug' => 'bakery'],
                [
                    'name_en' => 'Bakery',
                    'name_ar' => 'مخبوزات',
                    'description_en' => 'Fresh bread and bakery products',
                    'description_ar' => 'الخبز الطازج ومنتجات المخابز'
                ]
            ),
            'beverages' => Category::firstOrCreate(
                ['slug' => 'beverages'],
                [
                    'name_en' => 'Beverages',
                    'name_ar' => 'مشروبات',
                    'description_en' => 'Drinks and beverages',
                    'description_ar' => 'المشروبات والعصائر'
                ]
            ),
            'snacks' => Category::firstOrCreate(
                ['slug' => 'snacks'],
                [
                    'name_en' => 'Snacks',
                    'name_ar' => 'وجبات خفيفة',
                    'description_en' => 'Chips, nuts and snacks',
                    'description_ar' => 'رقائق، مكسرات ووجبات خفيفة'
                ]
            )
        ];

        // Fresh Produce products
        $produceCategory = $categories['fresh-produce'];
        $produceProducts = [
            [
                'name_en' => 'Fresh Apples',
                'name_ar' => 'تفاح طازج',
                'description_en' => 'Fresh red apples per kg',
                'description_ar' => 'تفاح أحمر طازج للكيلو',
                'image' => 'products/apples.jpg',
                'sku' => 'PROD-001',
                'price' => 5.99,
                'available_quantity' => 100,
                'category_id' => $produceCategory->id,
                'supplier_id' => $supplier->id
            ],
            [
                'name_en' => 'Carrots',
                'name_ar' => 'جزر',
                'description_en' => 'Fresh carrots per kg',
                'description_ar' => 'جزر طازج للكيلو',
                'image' => 'products/carrots.jpg',
                'sku' => 'PROD-002',
                'price' => 3.99,
                'available_quantity' => 150,
                'category_id' => $produceCategory->id,
                'supplier_id' => $supplier->id
            ],
        ];

        foreach ($produceProducts as $product) {
            $newProduct = Product::updateOrInsert(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'category_id' => $produceCategory->id,
                    'supplier_id' => $supplier->id
                ])
            );
        }

        // Dairy & Eggs products
        $dairyCategory = $categories['dairy-eggs'];
        $dairyProducts = [
            [
                'name_en' => 'Fresh Milk',
                'name_ar' => 'حليب طازج',
                'description_en' => 'Fresh cow milk 1L',
                'description_ar' => 'حليب بقر طازج 1 لتر',
                'image' => 'products/milk.jpg',
                'sku' => 'DAIRY-001',
                'price' => 4.99,
                'available_quantity' => 200,
                'category_id' => $dairyCategory->id,
                'supplier_id' => $supplier->id
            ],
            [
                'name_en' => 'Large Eggs',
                'name_ar' => 'بيض كبير',
                'description_en' => 'Fresh large eggs - 12 pieces',
                'description_ar' => 'بيض طازج كبير - 12 حبة',
                'image' => 'products/eggs.jpg',
                'sku' => 'DAIRY-002',
                'price' => 7.99,
                'available_quantity' => 150,
                'category_id' => $dairyCategory->id,
                'supplier_id' => $supplier->id
            ],
        ];

        foreach ($dairyProducts as $product) {
            $newProduct = Product::updateOrInsert(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'category_id' => $dairyCategory->id,
                    'supplier_id' => $supplier->id
                ])
            );
        }

        // Meat & Poultry products
        $meatCategory = $categories['meat-poultry'];
        $meatProducts = [
            [
                'name_en' => 'Chicken Breast',
                'name_ar' => 'صدور دجاج',
                'description_en' => 'Fresh chicken breast per kg',
                'description_ar' => 'صدور دجاج طازجة للكيلو',
                'image' => 'products/chicken-breast.jpg',
                'sku' => 'MEAT-001',
                'price' => 19.99,
                'available_quantity' => 100,
                'category_id' => $meatCategory->id,
                'supplier_id' => $supplier->id
            ],
            [
                'name_en' => 'Ground Beef',
                'name_ar' => 'لحم مفروم',
                'description_en' => 'Fresh ground beef per kg',
                'description_ar' => 'لحم بقر مفروم طازج للكيلو',
                'image' => 'products/ground-beef.jpg',
                'sku' => 'MEAT-002',
                'price' => 29.99,
                'available_quantity' => 80,
                'category_id' => $meatCategory->id,
                'supplier_id' => $supplier->id
            ],
        ];

        foreach ($meatProducts as $product) {
            $newProduct = Product::updateOrInsert(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'category_id' => $meatCategory->id,
                    'supplier_id' => $supplier->id
                ])
            );
        }

        // Bakery products
        $bakeryCategory = $categories['bakery'];
        $bakeryProducts = [
            [
                'name_en' => 'Arabic Bread',
                'name_ar' => 'خبز عربي',
                'description_en' => 'Fresh Arabic bread - pack of 5',
                'description_ar' => 'خبز عربي طازج - 5 قطع',
                'image' => 'products/arabic-bread.jpg',
                'sku' => 'BAKERY-001',
                'price' => 2.99,
                'available_quantity' => 200,
                'category_id' => $bakeryCategory->id,
                'supplier_id' => $supplier->id
            ],
            [
                'name_en' => 'Croissant',
                'name_ar' => 'كرواسون',
                'description_en' => 'Fresh butter croissant - 3 pieces',
                'description_ar' => 'كرواسون بالزبدة طازج - 3 قطع',
                'image' => 'products/croissant.jpg',
                'sku' => 'BAKERY-002',
                'price' => 8.99,
                'available_quantity' => 100,
                'category_id' => $bakeryCategory->id,
                'supplier_id' => $supplier->id
            ],
        ];

        foreach ($bakeryProducts as $product) {
            $newProduct = Product::updateOrInsert(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'category_id' => $bakeryCategory->id,
                    'supplier_id' => $supplier->id
                ])
            );
        }

        // Beverages products
        $beveragesCategory = $categories['beverages'];
        $beveragesProducts = [
            [
                'name_en' => 'Mineral Water',
                'name_ar' => 'مياه معدنية',
                'description_en' => 'Natural mineral water 1.5L',
                'description_ar' => 'مياه معدنية طبيعية 1.5 لتر',
                'image' => 'products/water.jpg',
                'sku' => 'BEV-001',
                'price' => 1.99,
                'available_quantity' => 500,
                'category_id' => $beveragesCategory->id,
                'supplier_id' => $supplier->id
            ],
            [
                'name_en' => 'Orange Juice',
                'name_ar' => 'عصير برتقال',
                'description_en' => 'Fresh orange juice 1L',
                'description_ar' => 'عصير برتقال طازج 1 لتر',
                'image' => 'products/orange-juice.jpg',
                'sku' => 'BEV-002',
                'price' => 9.99,
                'available_quantity' => 150,
                'category_id' => $beveragesCategory->id,
                'supplier_id' => $supplier->id
            ],
        ];

        foreach ($beveragesProducts as $product) {
            $newProduct = Product::updateOrInsert(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'category_id' => $beveragesCategory->id,
                    'supplier_id' => $supplier->id
                ])
            );
        }

        // Snacks products
        $snacksCategory = $categories['snacks'];
        $snacksProducts = [
            [
                'name_en' => 'Mixed Nuts',
                'name_ar' => 'مكسرات مشكلة',
                'description_en' => 'Premium mixed nuts 250g',
                'description_ar' => 'مكسرات مشكلة فاخرة 250 جرام',
                'image' => 'products/mixed-nuts.jpg',
                'sku' => 'SNACK-001',
                'price' => 15.99,
                'available_quantity' => 100,
                'category_id' => $snacksCategory->id,
                'supplier_id' => $supplier->id
            ],
            [
                'name_en' => 'Potato Chips',
                'name_ar' => 'شيبس بطاطس',
                'description_en' => 'Original flavor potato chips 150g',
                'description_ar' => 'شيبس بطاطس بالنكهة الأصلية 150 جرام',
                'image' => 'products/chips.jpg',
                'sku' => 'SNACK-002',
                'price' => 3.99,
                'available_quantity' => 200,
                'category_id' => $snacksCategory->id,
                'supplier_id' => $supplier->id
            ],
        ];

        foreach ($snacksProducts as $product) {
            $newProduct = Product::updateOrInsert(
                ['sku' => $product['sku']],
                array_merge($product, [
                    'category_id' => $snacksCategory->id,
                    'supplier_id' => $supplier->id
                ])
            );
        }
    }
}
