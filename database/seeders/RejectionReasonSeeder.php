<?php

namespace Database\Seeders;

use App\Models\RejectionReason;
use Illuminate\Database\Seeder;

class RejectionReasonSeeder extends Seeder
{
    public function run()
    {
        $reasons = [
            [
                'reason_en' => 'Products not available',
                'reason_ar' => 'المنتجات غير متوفرة'
            ],
            [
                'reason_en' => 'In a vacation',
                'reason_ar' => 'انا في عطلة'
            ],
            [
                'reason_en' => 'Out of working Hours',
                'reason_ar' => 'خارج وقت الدوام'
            ],
            // [
            //     'reason_en' => 'Delivery time too long',
            //     'reason_ar' => 'وقت التوصيل طويل جدًا'
            // ],
            // [
            //     'reason_en' => 'Customer changed mind',
            //     'reason_ar' => 'العميل غير رأيه'
            // ]
        ];

        foreach ($reasons as $reason) {
            RejectionReason::create($reason);
        }
    }
}
