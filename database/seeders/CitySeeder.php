<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run()
    {
        $cities = [
            ['name_ar' => 'الرياض', 'name_en' => 'Riyadh', 'code' => 'RYD'],
            ['name_ar' => 'جدة', 'name_en' => 'Jeddah', 'code' => 'JED'],
            ['name_ar' => 'مكة المكرمة', 'name_en' => 'Makkah', 'code' => 'MAK'],
            ['name_ar' => 'المدينة المنورة', 'name_en' => 'Madinah', 'code' => 'MED'],
            ['name_ar' => 'الدمام', 'name_en' => 'Dammam', 'code' => 'DMM'],
            ['name_ar' => 'الخبر', 'name_en' => 'Khobar', 'code' => 'KHB'],
            ['name_ar' => 'الطائف', 'name_en' => 'Taif', 'code' => 'TIF'],
            ['name_ar' => 'تبوك', 'name_en' => 'Tabuk', 'code' => 'TBK'],
            ['name_ar' => 'بريدة', 'name_en' => 'Buraidah', 'code' => 'BRD'],
            ['name_ar' => 'خميس مشيط', 'name_en' => 'Khamis Mushait', 'code' => 'KMS'],
        ];

        foreach ($cities as $city) {
            City::updateOrInsert($city);
        }
    }
}
