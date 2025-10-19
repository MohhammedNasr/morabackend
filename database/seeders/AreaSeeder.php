<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\City;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run()
    {
        // Get cities
        $riyadh = City::where('code', 'RYD')->first();
        $jeddah = City::where('code', 'JED')->first();

        $riyadhAreas = [
            ['name_ar' => 'المعذر', 'name_en' => 'Al Mathar', 'code' => 'RYD01'],
            ['name_ar' => 'الملز', 'name_en' => 'Al Malaz', 'code' => 'RYD02'],
            ['name_ar' => 'الربوة', 'name_en' => 'Al Rabwah', 'code' => 'RYD03'],
            ['name_ar' => 'العليا', 'name_en' => 'Al Olaya', 'code' => 'RYD04'],
            ['name_ar' => 'النخيل', 'name_en' => 'Al Nakheel', 'code' => 'RYD05'],
        ];

        $jeddahAreas = [
            ['name_ar' => 'النخيل', 'name_en' => 'Al Nakheel', 'code' => 'JED01'],
            ['name_ar' => 'السلامة', 'name_en' => 'Al Salamah', 'code' => 'JED02'],
            ['name_ar' => 'الزهراء', 'name_en' => 'Al Zahra', 'code' => 'JED03'],
            ['name_ar' => 'الروضة', 'name_en' => 'Al Rawdah', 'code' => 'JED04'],
            ['name_ar' => 'الشرفية', 'name_en' => 'Al Sharafiyah', 'code' => 'JED05'],
        ];

        foreach ($riyadhAreas as $area) {
            $area['city_id'] = $riyadh->id;
            Area::updateOrInsert($area);
        }

        foreach ($jeddahAreas as $area) {
            $area['city_id'] = $jeddah->id;
            Area::updateOrInsert($area);
        }
    }
}
