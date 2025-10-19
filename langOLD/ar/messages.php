<?php

return [
    // General
    'welcome' => 'مرحباً بكم في نظام إدارة العملاء',
    'not_available' => 'غير متاح',
    'select_date_range' => 'اختر نطاق التاريخ',
    'clear' => 'مسح',
    'currency_symbol' => 'ر.س',
    'actions' => 'الإجراءات',
    'date' => 'التاريخ',
    'id' => 'المعرف',
    'dashboard' => 'لوحة التحكم',
    'profile' => 'الملف الشخصي',
    'settings' => 'الإعدادات',
    'logout' => 'تسجيل الخروج',
    'hi' => 'مرحباً',
    'my_profile' => 'ملفي الشخصي',
    'account_settings' => 'إعدادات الحساب والمزيد',
    'sign_out' => 'تسجيل الخروج',
    'english' => 'الإنجليزية',
    'arabic' => 'العربية',
    'Dashbard' => 'لوحة التحكم',

    // Navigation
    'index' => 'الفهرس',
    'customers' => 'العملاء',
    'orders' => 'الطلبات',
    'products' => 'المنتجات',
    'reports' => 'التقارير',
    'inventory' => 'المخزون',
    'categories' => 'الفئات',
    'suppliers' => 'الموردون',
    'stores' => 'المتاجر',

    // Actions
    'add' => 'إضافة',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'save' => 'حفظ',
    'cancel' => 'إلغاء',
    'search' => 'بحث',
    'view' => 'عرض',
    'update' => 'تحديث',
    'create' => 'إنشاء',
    'import' => 'استيراد',
    'export' => 'تصدير',

    // Status
    'active' => 'نشط',
    'inactive' => 'غير نشط',
    'pending' => 'قيد الانتظار',
    'completed' => 'مكتمل',
    'approved' => 'تم الموافقة',
    'rejected' => 'مرفوض',
    'processing' => 'قيد المعالجة',
    'shipped' => 'تم الشحن',
    'delivered' => 'تم التسليم',

    // Messages
    'success' => 'نجاح',
    'error' => 'خطأ',
    'warning' => 'تحذير',
    'info' => 'معلومات',
    'confirm_delete' => 'هل أنت متأكد من حذف هذا العنصر؟',
    'item_deleted' => 'تم حذف العنصر بنجاح',
    'item_created' => 'تم إنشاء العنصر بنجاح',
    'item_updated' => 'تم تحديث العنصر بنجاح',

    // Forms
    'name' => 'الاسم',
    'email' => 'البريد الإلكتروني',
    'phone' => 'رقم الهاتف',
    'address' => 'العنوان',
    'description' => 'الوصف',
    'price' => 'السعر',
    'quantity' => 'الكمية',
    'status' => 'الحالة',
    'image' => 'الصورة',
    'select' => 'اختر',
    'submit' => 'إرسال',
    'reset' => 'إعادة تعيين',

    // Authentication
    'login' => 'تسجيل الدخول',
    'register' => 'تسجيل',
    'forgot_password' => 'نسيت كلمة المرور؟',
    'remember_me' => 'تذكرني',
    'password' => 'كلمة المرور',
    'confirm_password' => 'تأكيد كلمة المرور',
    'reset_password' => 'إعادة تعيين كلمة المرور',

    // User
    'users' => 'المستخدمون',
    'roles' => 'الأدوار',
    'permissions' => 'الصلاحيات',
    'user_management' => 'إدارة المستخدمين',

    // Orders
    'order_number' => 'رقم الطلب',
    'order_date' => 'تاريخ الطلب',
    'order_status' => 'حالة الطلب',
    'order_total' => 'إجمالي الطلب',
    'order_items' => 'عناصر الطلب',
    'shipping_address' => 'عنوان الشحن',
    'billing_address' => 'عنوان الفاتورة',

    // Products
    'product_name' => 'اسم المنتج',
    'product_code' => 'رمز المنتج',
    'product_category' => 'فئة المنتج',
    'product_price' => 'سعر المنتج',
    'product_stock' => 'مخزون المنتج',
    'product_description' => 'وصف المنتج',

    // Customers
    'customer_name' => 'اسم العميل',
    'customer_email' => 'بريد العميل',
    'customer_phone' => 'هاتف العميل',
    'customer_address' => 'عنوان العميل',
    'customer_orders' => 'طلبات العميل',

    // Suppliers
    'supplier_name' => 'اسم المورد',
    'supplier_email' => 'بريد المورد',
    'supplier_phone' => 'هاتف المورد',
    'supplier_address' => 'عنوان المورد',
    'supplier_products' => 'منتجات المورد',

    // Stores
    'store_name' => 'اسم المتجر',
    'store_email' => 'بريد المتجر',
    'store_phone' => 'هاتف المتجر',
    'store_address' => 'عنوان المتجر',
    'store_owner' => 'مالك المتجر',
    'store_branches' => 'فروع المتجر',
    'filter' => 'تصفية',
    // Orders Module
    'orders' => [
        'title' => 'إدارة الطلبات',
        'buttons' => [
            'add' => 'إضافة طلب',
            'reset' => 'إعادة تعيين الفلاتر'
        ],
        'filters' => [
            'search' => 'بحث في الطلبات...',
            'all_stores' => 'جميع المتاجر',
            'all_suppliers' => 'جميع الموردين',
            'all_statuses' => 'جميع الحالات',
            'all_methods' => 'جميع الطرق'
        ],
        'table_headers' => [
            'store' => 'المتجر',
            'supplier' => 'المورد',
            'status' => 'الحالة',
            'payment_method' => 'طريقة الدفع',
            'shipping_method' => 'طريقة الشحن'
        ],
        'status' => [
            'pending' => 'قيد الانتظار',
            'approved' => 'تمت الموافقة',
            'rejected' => 'مرفوض',
            'delivered' => 'تم التسليم'
        ],
        'payment_method' => [
            'cash' => 'نقداً',
            'credit_card' => 'بطاقة ائتمان',
            'bank_transfer' => 'تحويل بنكي'
        ],
        'shipping_method' => [
            'standard' => 'عادي',
            'express' => 'سريع',
            'pickup' => 'استلام'
        ],
        'date_range' => 'نطاق التاريخ',
        'order_type_label' => 'نوع الطلب',
        'order_types' => [
            'regular' => 'عادي',
            'express' => 'سريع',
            'bulk' => 'كمي'
        ]
    ]
];
