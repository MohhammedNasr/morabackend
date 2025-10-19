<?php

namespace Database\Seeders;

use App\Models\RejectionReason;
use Illuminate\Database\Seeder;

class RejectionReasonsSeeder extends Seeder
{
    public function run(): void
    {
        // Supplier rejection reasons
        $supplierReasons = [
            ['reason_en' => 'Product out of stock', 'reason_ar' => 'المنتج غير متوفر', 'type' => 'supplier'],
            ['reason_en' => 'Price mismatch', 'reason_ar' => 'عدم تطابق السعر', 'type' => 'supplier'],
            ['reason_en' => 'Delivery time too short', 'reason_ar' => 'وقت التسليم قصير جداً', 'type' => 'supplier'],
            ['reason_en' => 'Order quantity too large', 'reason_ar' => 'الكمية المطلوبة كبيرة جداً', 'type' => 'supplier'],
            ['reason_en' => 'Product specifications not matching', 'reason_ar' => 'مواصفات المنتج غير متطابقة', 'type' => 'supplier'],
        ];

        // Representative rejection reasons
        $representativeReasons = [
            ['reason_en' => 'Delivery location too far', 'reason_ar' => 'موقع التسليم بعيد جداً', 'type' => 'representative'],
            ['reason_en' => 'Insufficient delivery time', 'reason_ar' => 'وقت التسليم غير كاف', 'type' => 'representative'],
            ['reason_en' => 'Unsafe delivery area', 'reason_ar' => 'منطقة التسليم غير آمنة', 'type' => 'representative'],
            ['reason_en' => 'Vehicle issues', 'reason_ar' => 'مشاكل في المركبة', 'type' => 'representative'],
            ['reason_en' => 'Personal unavailability', 'reason_ar' => 'عدم التوفر الشخصي', 'type' => 'representative'],
        ];

        // Insert all reasons
        RejectionReason::insert(array_merge($supplierReasons, $representativeReasons));
    }
}
