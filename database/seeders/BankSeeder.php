<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            ['name_en' => 'Al Rajhi Bank', 'name_ar' => 'البنك الأهلي التجاري', 'slug' => 'al-rajhi'],
            ['name_en' => 'Saudi National Bank', 'name_ar' => 'البنك الأهلي السعودي', 'slug' => 'snb'],
            ['name_en' => 'Riyad Bank', 'name_ar' => 'بنك الرياض', 'slug' => 'riyad-bank'],
            ['name_en' => 'Alinma Bank', 'name_ar' => 'بنك الإنماء', 'slug' => 'alinma'],
            ['name_en' => 'Arab National Bank', 'name_ar' => 'البنك العربي الوطني', 'slug' => 'anb'],
            ['name_en' => 'Saudi British Bank', 'name_ar' => 'البنك السعودي البريطاني', 'slug' => 'sabb'],
            ['name_en' => 'Saudi Investment Bank', 'name_ar' => 'البنك السعودي للاستثمار', 'slug' => 'saib'],
            ['name_en' => 'Bank AlJazira', 'name_ar' => 'بنك الجزيرة', 'slug' => 'al-jazira'],
            ['name_en' => 'Bank Albilad', 'name_ar' => 'بنك البلاد', 'slug' => 'al-bilad'],
            ['name_en' => 'Gulf International Bank', 'name_ar' => 'بنك الخليج الدولي', 'slug' => 'gib'],
        ];

        Bank::insert($banks);
    }
}
