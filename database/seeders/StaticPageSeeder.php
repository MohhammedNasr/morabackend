<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run()
    {
        StaticPage::updateOrCreate(
            ['id' => 1],
            [
                'title_en' => 'Terms and Conditions',
                'title_ar' => 'الشروط والأحكام',
                'details_en' => 'Please enter your terms and conditions in English here...',
                'details_ar' => 'الرجاء إدخال الشروط والأحكام باللغة العربية هنا...'
            ]
        );
    }
}
