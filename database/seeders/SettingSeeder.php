<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingSeeder extends Seeder
{
    public function run()
    {
        Setting::create([
            'name' => 'Mora',
            'logo' => 'brand/logo.png',
            'description' => 'Mora - Your Trusted B2B Platform',
            'primary_color' => '#007bff',
            'secondary_color' => '#6c757d',
            'email' => 'contact@mora.com',
            'phone' => '+966500000000',
            'phone2' => '+966500000001',
            'address' => 'Riyadh, Saudi Arabia',
            'latitude' => '24.7136',
            'longitude' => '46.6753',
            'facebook' => 'https://facebook.com/mora',
            'instagram' => 'https://instagram.com/mora',
            'twitter' => 'https://twitter.com/mora',
            'youtube' => 'https://youtube.com/mora',
            'whatsapp' => '+966500000000',
            'currency_name_en' => 'Saudi Riyal',
            'currency_name_ar' => 'ريال سعودي',
            'currency_symbol_en' => 'SAR',
            'currency_symbol_ar' => 'ر.س',
        ]);

        // Copy logo to storage
        if (!Storage::disk('public')->exists('brand/logo.png')) {
            Storage::disk('public')->put('brand/logo.png', file_get_contents(public_path('images/brand/mora.png')));
        }
    }
}
