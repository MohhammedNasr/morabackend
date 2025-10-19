<?php

return [
    'sub_total' => 'المجموع الفرعي (بدون الضريبة)',
    'discount' => 'الخصم',
    'unit_price' => 'سعر الوحدة',
    'total_price' => 'السعر الإجمالي',
    'table_headers' => [
        'reference' => 'رقم المرجع',
        'supplier' => 'المورد',
        'contact' => 'معلومات الاتصال',
        'status' => 'الحالة',
        'items' => 'المنتجات',
        'unit_price' => 'سعر الوحدة',
        'quantity' => 'الكمية',
        'total' => 'المجموع',
        'payment_status' => 'حالة الدفع',
        'delivery_status' => 'حالة التوصيل'
    ],
    'status' => [
        'pending' => 'قيد الانتظار',
        'acceptedBySupplier' => 'تم القبول من المورد',
        'rejectedBySupplier' => 'تم الرفض من المورد',
        'assignToRep' => 'تم التعيين للمندوب',
        'rejectedByRep' => 'تم الرفض من المندوب',
        'acceptedByRep' => 'تم القبول من المندوب',
        'modifiedBySupplier' => 'تم التعديل من المورد',
        'modifiedByRep' => 'تم التعديل من المندوب',
        'outForDelivery' => 'في الطريق للتوصيل',
        'delivered' => 'تم التوصيل'
    ],
    'delivery' => [
        'delivered' => 'تم التوصيل',
        'scheduled' => 'مجدول',
        'not_set' => 'غير محدد',
        'not_delivered' => "لم يتم التوصيل"
    ],
    'payment' => [
        'paid' => 'مدفوع',
        'pending' => 'قيد الانتظار'
    ],
    'timeline' => [
        'payment_created' => 'تم إنشاء الدفع',
        'payment_updated' => 'تم تحديث الدفع',
        'payment_deleted' => 'تم حذف الدفع',
        'status_changed' => 'تم تغيير الحالة إلى :status',
        'Payment phase 1' => 'مرحلة الدفع 1',
        'Payment phase 2' => 'مرحلة الدفع 2',
        'Payment phase 3' => 'مرحلة الدفع 3',
        'Payment phase 4' => 'مرحلة الدفع 4'
    ],
    'datatable' => [
        'reference' => 'رقم المرجع',
        'products' => 'عدد المنتجات',
        'sub_total' => 'المجموع الفرعي',
        'discount' => 'الخصم',
        'total_amount' => 'المبلغ الإجمالي',
        'date' => 'التاريخ',
        'actions' => 'إجراءات',
        'status' => 'الحالة',
        'select_date_range' => 'اختر نطاق التاريخ'
    ]
];
