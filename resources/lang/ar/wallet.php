<?php

return [
    'title' => 'المحفظة',
    'balance' => [
        'current' => 'الرصيد الحالي',
        'credit_limit' => 'حد الائتمان',
        'available_credit' => 'الائتمان المتاح'
    ],
    'actions' => [
        'charge' => 'شحن المحفظة',
        'quick_actions' => 'إجراءات سريعة',
        'view_transactions' => 'عرض المعاملات',
        'create_order' => 'إنشاء طلب'
    ],
    'transactions' => [
        'title' => 'المعاملات الحديثة',
        'empty' => 'لا توجد معاملات',
        'columns' => [
            'date' => 'التاريخ',
            'type' => 'النوع',
            'amount' => 'المبلغ',
            'reference' => 'المرجع',
            'status' => 'الحالة'
        ],
        'types' => [
            'deposit' => 'إيداع',
            'withdrawal' => 'سحب'
        ],
        'statuses' => [
            'completed' => 'مكتمل',
            'pending' => 'قيد الانتظار',
            'failed' => 'فشل'
        ]
    ]
];
