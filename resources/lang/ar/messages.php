<?php

return [
    'create' => 'إنشاء',
    'password' => 'كلمة المرور',
    'submit' => 'إرسال',
    'remove_current_image' => 'إزالة الصورة الحالية',
    'choose_new_image' => 'اختيار صورة جديدة',
    'cancel' => 'إلغاء',
    'all_categories' => 'جميع الاصناف',
    'search' => 'بحث',
    'all_stores' => 'جميع المتاجر',
    'all_suppliers' => 'جميع الموردين',
    'store_menu' => [
        'dashboard' => 'لوحة التحكم',
        'orders' => 'الطلبات',
        'wallet' => 'المحفظة',
        'branches' => 'الفروع',
        'profile' => 'الملف الشخصي'
    ],
    'profile' => [
        'settings' => 'إعدادات الملف الشخصي',
        'information' => 'معلومات الملف الشخصي',
        'owner_name' => 'اسم المالك',
        'store_name' => 'اسم المتجر',
        'email' => 'البريد الإلكتروني',
        'phone' => 'الهاتف',
        'commercial_record' => 'السجل التجاري',
        'tax_record' => 'السجل الضريبي',
        'credit_limit' => 'الحد الائتماني',
        'save_changes' => 'حفظ التغييرات',
        'phone_cannot_change' => 'لا يمكن تغيير رقم الهاتف',
        'commercial_cannot_change' => 'لا يمكن تغيير السجل التجاري',
        'credit_limit_managed' => 'الحد الائتماني يتم إدارته بواسطة إدارة مورا'
    ],
    'branches' => [
        'title' => 'إدارة الفروع',
        'create' => 'إضافة فرع',
        'edit' => 'تعديل فرع',
        'table_headers' => [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'main_name' => 'الاسم الرئيسي',
            'street' => 'الشارع',
            'building' => 'المبنى',
            'floor' => 'الطابق',
            'phone' => 'الهاتف',
            'city' => 'المدينة',
            'area' => 'المنطقة',
            'coordinates' => 'الإحداثيات',
            'main_branch' => 'الفرع الرئيسي',
            'status' => 'الحالة',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'all' => 'الكل'
        ],
        'main_branch' => [
            'yes' => 'نعم',
            'no' => 'لا'
        ],
        'datatable' => [
            'empty' => 'لا توجد فروع',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'info_empty' => 'عرض 0 إلى 0 من 0 مدخل',
            'search' => 'بحث:',
            'processing' => 'جاري المعالجة...',
            'length_menu' => 'عرض _MENU_ مدخل'
        ],
        'address' => 'العنوان',
        'latitude' => 'خط العرض',
        'longitude' => 'خط الطول',
        'notes' => 'ملاحظات'
    ],
    'currency_symbol' => "ر.س",

    'dashboard' => [
        'title' => 'لوحة تحكم المسؤول',
        'stats' => [
            'stores' => 'إجمالي المتاجر',
            'suppliers' => 'إجمالي الموردين',
            'orders' => 'إجمالي الطلبات',
            'products' => 'إجمالي المنتجات',
            'revenue' => 'الإيرادات',
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'pending' => 'قيد الانتظار',
            'verified' => 'تم التحقق',
            'under_processing' => 'قيد المعالجة',
            'completed' => 'مكتمل',
            'canceled' => 'ملغى'
        ],
        'charts' => [
            'order_trends' => 'اتجاهات الطلبات',
            'store_performance' => 'أداء المتاجر'
        ],
        'tables' => [
            'recent_orders' => 'الطلبات الحديثة',
            'recent_stores' => 'المتاجر الحديثة'
        ],
        'buttons' => [
            'view_all' => 'عرض الكل'
        ],
        'table_headers' => [
            'order_number' => 'رقم الطلب',
            'store' => 'المتجر',
            'amount' => 'المبلغ',
            'status' => 'الحالة'
        ],
        'empty_states' => [
            'no_orders' => 'لا توجد طلبات حديثة',
            'no_stores' => 'لا توجد متاجر حديثة'
        ],
    ],
    'Dashboard' => 'لوحة التحكم',
    'filter' => 'تصنيف',
    'date' => 'التاريخ',
    'actions' => 'الاجراءات',
    'Admin Management' => 'إدارة المسؤولين',
    'Users' => 'المستخدمين',
    'Admins' => 'المسؤولين',
    'Stores' => 'المتاجر',
    'All Stores' => 'جميع المتاجر',
    'Suppliers' => 'الموردين',
    'All Suppliers' => 'جميع الموردين',
    'Products' => 'المنتجات',
    'All Products' => 'جميع المنتجات',
    'Categories' => 'الفئات',
    'categories' => [
        'title' => 'إدارة الفئات',
        'create' => 'إنشاء فئة',
        'table_headers' => [
            'id' => 'الرقم',
            'image' => 'الصورة',
            'name_en' => 'الاسم (إنجليزي)',
            'name_ar' => 'الاسم (عربي)',
            'status' => 'الحالة',
            'products' => 'المنتجات',
            'actions' => 'إجراءات'
        ],
        'filters' => [
            'search' => 'بحث...',
            'all_statuses' => 'جميع الحالات',
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'reset' => 'إعادة تعيين'
        ],
        'messages' => [
            'created' => 'تم إنشاء الفئة بنجاح',
            'updated' => 'تم تحديث الفئة بنجاح',
            'deleted' => 'تم حذف الفئة بنجاح'
        ]
    ],
    'admin' => [
        'breadcrumbs' => [
            'admin' => 'المسؤول',
            'dashboard' => 'لوحة تحكم المسؤول'
        ]
    ],
    'products' => [
        'import' => 'استيراد المنتجات',
        'Import Errors' => 'أخطاء الاستيراد',

        'select_file' => 'اختر الملف',
        'choose_file' => 'اختيار ملف',
        'allowed_file_types' => 'أنواع الملفات المسموحة: xlsx, xls, csv',
        'import' => 'استيراد',
        'import_success' => 'تم استيراد المنتجات بنجاح',
        'import_error' => 'حدث خطأ أثناء الاستيراد: ',
        'validation' => [
            'store_id' => 'معرف المتجر',
            'category_id' => 'معرف الفئة',
            'name_en' => 'الاسم بالإنجليزية',
            'name_ar' => 'الاسم بالعربية',
            'sku' => 'SKU',
            'price' => 'السعر',
            'available_quantity' => 'الكمية المتاحة',
            'status' => 'الحالة',
            'required' => 'حقل :attribute مطلوب',
            'exists' => ':attribute غير صالح',
            'string' => 'يجب أن يكون :attribute نصاً',
            'max' => 'يجب ألا يتجاوز :attribute :max حرف',
            'numeric' => 'يجب أن يكون :attribute رقماً',
            'min' => 'يجب أن يكون :attribute على الأقل :min',
            'integer' => 'يجب أن يكون :attribute عدداً صحيحاً',
            'in' => ':attribute المحدد غير صالح',
            'boolean' => 'يجب أن يكون :attribute صواب أو خطأ',
            'unique' => 'هذا :attribute مستخدم بالفعل',
            'sku.unique' => 'هذا الكود SKU مستخدم بالفعل'
        ]
    ],
    'datatable' => [
        'empty' => 'لا توجد بيانات متاحة',
        'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
        'search' => 'بحث:',
        'info_empty' => 'عرض 0 إلى 0 من 0 مدخل',
        'info_filtered' => '(منتقاة من مجموع _MAX_ مدخل)',
        'length_menu' => 'عرض _MENU_ مدخل',
        'loading' => 'جاري التحميل...',
        'search' => 'بحث:',
        'zero_records' => 'لا توجد سجلات مطابقة'
    ],
    'Promotions' => 'العروض',
    'Orders' => 'الطلبات',
    'All Orders' => 'جميع الطلبات',
    'Sub Orders' => 'الطلبات الفرعية',
    'Finance' => 'المالية',
    'Wallets' => 'المحافظ',
    'Representatives' => 'المندوبين',
    'All Representatives' => 'جميع المندوبين',
    'Admin' => 'المسؤول',
    'Cities' => 'المدن',
    'Areas' => 'المناطق',
    'Banners' => 'اللافتات',
    'Settings' => 'الإعدادات',
    'Reports' => 'التقارير',
    'About' => 'حول',
    'Team' => 'الفريق',
    'Contact' => 'اتصل بنا',
    'notifications' => [
        'new_order' => 'تم استلام طلب جديد',
        'new_customer' => 'تم تسجيل عميل جديد',
        'time_ago' => 'منذ :time'
    ],
    'profile' => [
        'my_profile' => 'ملفي الشخصي',
        'account_settings' => 'إعدادات الحساب والمزيد',
        'sign_out' => 'تسجيل الخروج'
    ],
    'users' => [
        'title' => 'إدارة المستخدمين',
        'create' => 'إنشاء مستخدم',
        'edit' => 'تعديل مستخدم',
        'delete' => 'حذف مستخدم',
        'show' => 'عرض مستخدم',
        'table_headers' => [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'roles' => 'الأدوار',
            'stores' => 'المتاجر',
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'buttons' => [
            'add' => 'إضافة مستخدم',
            'save' => 'حفظ',
            'cancel' => 'إلغاء'
        ],
        'filters' => [
            'status' => 'الحالة',
            'role' => 'الدور',
            'date_range' => 'نطاق التاريخ'
        ],
        'messages' => [
            'created' => 'تم إنشاء المستخدم بنجاح',
            'updated' => 'تم تحديث المستخدم بنجاح',
            'deleted' => 'تم حذف المستخدم بنجاح'
        ],
        'validation' => [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'password' => 'كلمة المرور',
            'password_confirmation' => 'تأكيد كلمة المرور',
            'role' => 'الدور'
        ],
        'datatable' => [
            'processing' => 'جاري المعالجة...',
            'emptyTable' => 'لا توجد بيانات متاحة',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'infoEmpty' => 'عرض 0 إلى 0 من 0 مدخل',
            'infoFiltered' => '(منتقاة من مجموع _MAX_ مدخل)',
            'lengthMenu' => 'عرض _MENU_ مدخل',
            'loadingRecords' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zeroRecords' => 'لا توجد سجلات مطابقة'
        ],
    ],
    'stores' => [
        'title' => 'إدارة المتاجر',
        'index' => 'قائمة المتاجر',
        'create' => 'إنشاء متجر',
        'edit' => 'تعديل متجر',
        'show' => 'عرض المتجر',
        'delete' => 'حذف متجر',
        'import' => 'استيراد متاجر',
        'buttons' => [
            'add' => 'إضافة متجر',
            'save' => 'حفظ',
            'cancel' => 'إلغاء',
            'import' => 'استيراد'
        ],
        'filters' => [
            'search' => 'بحث...',
            'all_statuses' => 'جميع الحالات'
        ],
        'table_headers' => [
            'id' => 'الرقم',
            'name' => 'اسم المتجر',
            'owner_name' => 'اسم المالك',
            'owner_email' => 'بريد المالك',
            'phone' => 'الهاتف',
            'commercial_record' => 'السجل التجاري',
            'credit_limit' => 'الحد الائتماني',
            'status' => 'الحالة',
            'orders' => 'الطلبات',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات',
            'Verified' => 'تم التحقق',
            'Active' => 'مفعل',
            'auto_verify' => 'تاكيد اتوماتيكا'

        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'messages' => [
            'created' => 'تم إنشاء المتجر بنجاح',
            'updated' => 'تم تحديث المتجر بنجاح',
            'deleted' => 'تم حذف المتجر بنجاح',
            'imported' => 'تم استيراد المتاجر بنجاح'
        ],
        'validation' => [
            'name' => 'اسم المتجر',
            'description' => 'الوصف'
        ],
        'datatable' => [
            'processing' => 'جاري المعالجة...',
            'emptyTable' => 'لا توجد متاجر',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ متجر',
            'infoEmpty' => 'عرض 0 إلى 0 من 0 متجر',
            'infoFiltered' => '(منتقاة من مجموع _MAX_ متجر)',
            'lengthMenu' => 'عرض _MENU_ متجر',
            'loadingRecords' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zeroRecords' => 'لا توجد متاجر مطابقة'
        ]
    ],
    'orders' => [
        'search' => 'بحث',
        'all_stores' => 'جميع المتاجر',
        'all_suppliers' => 'جميع الموردين',
        'select_date_range_label' => 'اختيار نطاق التاريخ',
        'status' => [
            'canceled' => 'ملغي',
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'shipped' => 'تم الشحن',
            'delivered' => 'تم التوصيل',
            'cancelled' => 'ملغي',
            'verified' => 'تم التحقق',
            'under_processing' => 'تحت المعالجة',



        ],
        'details' => [
            'title' => 'تفاصيل الطلب #:number',
            'suborder_info' => 'معلومات الطلب الفرعي',
            'reference_number' => 'الرقم المرجعي',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
            'total_amount' => 'المبلغ الإجمالي',
            'store_info' => 'المتجر والفرع',
            'store' => 'المتجر',
            'contact' => 'جهة الاتصال',
            'phone' => 'الهاتف',
            'status' => 'الحالة',
            'date' => 'التاريخ',
            'rejection_reason' => 'سبب الرفض',
            'delivered_at' => 'تاريخ التسليم',
            'not_delivered' => 'لم يتم التسليم',
            'products' => 'المنتجات',
            'sub_total' => 'المجموع الفرعي (بدون الضريبة)',
            'discount' => 'الخصم',
            'no_products' => 'لا توجد منتجات لهذا الطلب',
            'image' => 'الصورة',
            'sku' => 'SKU',
            'product' => 'المنتج',
            'quantity' => 'الكمية',
            'price' => 'سعر الوحدة',
            'total' => 'الإجمالي',
            'weight' => 'الوزن',
            'dimensions' => 'الأبعاد',
            'description' => 'الوصف',
            'contact_phone' => 'هاتف الاتصال',
            'contact_email' => 'البريد الإلكتروني',
            'address' => 'العنوان',
            'location' => 'الموقع',
            'view_on_map' => 'عرض على الخريطة',
            'actions_title' => 'إجراءات الطلب',
            'actions' => [
                'accept_by_supplier' => 'تم القبول من المورد',
                'reject_by_supplier' => 'رفض الطلب',
                'deliver' => 'تم التوصيل',
                'cancel' => 'إلغاء',
                'confirm_rejection' => 'تأكيد الرفض'
            ],
            'modal' => [
                'reject_title' => 'رفض الطلب',
                'reason_label' => 'السبب'
            ]
        ],
        'title' => 'إدارة الطلبات',
        'index' => 'قائمة الطلبات',
        'create' => 'إنشاء طلب',
        'show' => 'عرض الطلب',
        'date_range' => 'اختر التاريخ',
        'order_type_label' => 'نوع الطلب',
        'table_headers' => [
            'id' => 'الرقم',
            'store' => 'المتجر',
            'supplier' => 'المورد',
            'status' => 'الحالة',
            'total' => 'الإجمالي',
            'sub_orders' => 'الطلبات الفرعية',
            'payment_method' => 'طريقة الدفع',
            'shipping_address' => 'عنوان الشحن',
            'shipping_method' => 'طريقة الدفع',
            'date' => 'التاريخ',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'pending' => 'قيد الانتظار',
            'approved' => 'تم الموافقة',
            'rejected' => 'مرفوض',
            'delivered' => 'تم التوصيل'
        ],
        'payment_method' => [
            'cash' => 'نقدي',
            'credit_card' => 'بطاقة ائتمان',
            'bank_transfer' => 'تحويل بنكي'
        ],
        'select_date_range' => 'اختر نطاق التاريخ',
        'shipping_method' => [
            'standard' => 'عادي',
            'express' => 'سريع',
            'pickup' => 'استلام'
        ],
        'shipping_method_label' => 'وسيلة الشحن',
        'order_type_label' => 'نوع الطلب',
        'order_type' => [
            'regular' => 'عادي',
            'express' => 'سريع',
            'bulk' => 'كمي'
        ],
        'buttons' => [
            'add' => 'إضافة طلب',
            'filter' => 'تصفية',
            'reset' => 'إعادة تعيين'
        ],
        'filters' => [
            'search' => 'بحث...',
            'all_stores' => 'جميع المتاجر',
            'all_suppliers' => 'جميع الموردين',
            'all_statuses' => 'جميع الحالات',
            'all_methods' => 'جميع الطرق',
            'all_types' => 'جميع الأنواع',
            'date_range' => 'نطاق التاريخ'
        ],
        'messages' => [
            'created' => 'تم إنشاء الطلب بنجاح',
            'updated' => 'تم تحديث الطلب بنجاح',
            'deleted' => 'تم حذف الطلب بنجاح'
        ]
    ],
    'suppliers' => [
        'title' => 'إدارة الموردين',
        'buttons' => [
            'add' => 'إضافة مورد',
            'reset' => 'إعادة تعيين'
        ],
        'filters' => [
            'search' => 'بحث...',
            'status' => 'الحالة',
            'all_statuses' => 'جميع الحالات'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'table_headers' => [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'contact_name' => 'اسم الاتصال',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'address' => 'العنوان',
            'commercial_record' => 'السجل التجاري',
            'payment_terms' => 'شروط الدفع',
            'status' => 'الحالة',
            'is_verified' => 'حالة التحقق',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات'
        ],
        'datatable' => [
            'processing' => 'جاري المعالجة...',
            'emptyTable' => 'لا توجد بيانات متاحة',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'infoEmpty' => 'عرض 0 إلى 0 من 0 مدخل',
            'infoFiltered' => '(منتقاة من مجموع _MAX_ مدخل)',
            'lengthMenu' => 'عرض _MENU_ مدخل',
            'loadingRecords' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zeroRecords' => 'لا توجد سجلات مطابقة'
        ]
    ],
    'products' => [
        'title' => 'إدارة المنتجات',
        'choose_excel' => 'اختر الملف',
        'allowed_file_types' => 'انواع الملفات المتاحة',
        'import' => 'استيراد من اكسيل',
        'index' => 'قائمة المنتجات',
        'description_ar' => 'الوصف (عربي)',
        'description_en' => 'الوصف (إنجليزي)',
        'product_image' => 'صورة المنتج',
        'create' => 'إنشاء منتج',
        'edit' => 'تعديل منتج',
        'show' => 'عرض المنتج',
        'delete' => 'حذف منتج',
        'import' => 'استيراد منتجات',
        'choose_file' => 'اختر صورة المنتج',
        'select_category' => 'اخترالصنف',
        'add_new' => 'اضف جديد',
        'update' => 'تحديث',
        'buttons' => [
            'add' => 'إضافة منتج',
            'save' => 'حفظ',
            'cancel' => 'إلغاء',
            'import' => 'استيراد'
        ],
        'filters' => [
            'search' => 'بحث...',
            'all_statuses' => 'جميع الحالات',
            'all_categories' => 'جميع الفئات'
        ],
        'table_headers' => [
            'id' => 'الرقم',
            'image' => 'الصورة',
            'name_en' => 'الاسم (إنجليزي)',
            'name_ar' => 'الاسم (عربي)',
            'sku' => 'SKU',
            'price' => 'السعر',
            'category' => 'الفئة',
            'status' => 'الحالة',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'active' => 'نشط',
            'deleted' => 'محذوف'
        ],
        'messages' => [
            'created' => 'تم إنشاء المنتج بنجاح',
            'updated' => 'تم تحديث المنتج بنجاح',
            'deleted' => 'تم حذف المنتج بنجاح',
            'imported' => 'تم استيراد المنتجات بنجاح'
        ],
        'validation' => [
            'name_en' => 'الاسم (إنجليزي)',
            'name_ar' => 'الاسم (عربي)',
            'price' => 'السعر'
        ],
        'datatable' => [
            'processing' => 'جاري المعالجة...',
            'emptyTable' => 'لا توجد منتجات',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ منتج',
            'infoEmpty' => 'عرض 0 إلى 0 من 0 منتج',
            'infoFiltered' => '(منتقاة من مجموع _MAX_ منتج)',
            'lengthMenu' => 'عرض _MENU_ منتج',
            'loadingRecords' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zeroRecords' => 'لا توجد منتجات مطابقة'
        ]
    ],
    'wallets' => [
        'title' => 'إدارة المحافظ',
        'index' => 'قائمة المحافظ',
        'show' => 'عرض المحفظة',
        'transactions' => 'المعاملات',
        'stores' => [
            'table_headers' => [
                'auto_verify' => 'التحقق التلقائي للطلبات'
            ]
        ],
        'table_headers' => [
            'id' => 'الرقم',
            'owner' => 'المالك',
            'balance' => 'الرصيد',
            'status' => 'الحالة',
            'last_transaction' => 'آخر معاملة',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'active' => 'نشط',
            'frozen' => 'مجمد',
            'closed' => 'مغلق'
        ],
        'buttons' => [
            'add_funds' => 'إضافة رصيد',
            'withdraw' => 'سحب',
            'freeze' => 'تجديد',
            'unfreeze' => 'إلغاء التجديد'
        ],
        'messages' => [
            'funds_added' => 'تم إضافة الرصيد بنجاح',
            'withdrawn' => 'تم السحب بنجاح',
            'frozen' => 'تم تجديد المحفظة',
            'unfrozen' => 'تم إلغاء التجديد'
        ]
    ],
    'representatives' => [
        'create_title' => 'إنشاء مندوب',
        'modals' => [
            'edit_title' => 'تعديل المندوب',
            'name' => 'الاسم',
            'phone' => 'الهاتف',
            'email' => 'البريد الإلكتروني',
            'password' => 'كلمة المرور',
            'password_optional' => 'كلمة المرور (اتركه فارغاً للحفاظ على الحالي)',
            'password_placeholder' => 'اتركه فارغاً للحفاظ على الحالي',
            'supplier' => 'المورد',
            'close' => 'إغلاق',
            'update' => 'تحديث',
            'assign_title' => 'تعيين مندوب',
            'select_representative' => 'اختر مندوب',
            'assign' => 'تعيين'
        ],
        'title' => 'إدارة المندوبين',
        'index' => 'قائمة المندوبين',
        'create' => 'إنشاء مندوب',
        'edit' => 'تعديل مندوب',
        'show' => 'عرض المندوب',
        'delete' => 'حذف مندوب',
        'assign' => 'تعيين مندوب',
        'table_headers' => [
            'id' => 'الرقم',
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'phone' => 'الهاتف',
            'vehicle_type' => 'نوع المركبة',
            'status' => 'الحالة',
            'orders_count' => 'عدد الطلبات',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات',
            'assigned_orders' => 'الطلبات المعينة'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'on_duty' => 'في الخدمة',
            'off_duty' => 'خارج الخدمة'
        ],
        'vehicle_type' => [
            'motorcycle' => 'دراجة نارية',
            'car' => 'سيارة',
            'truck' => 'شاحنة'
        ],
        'buttons' => [
            'add' => 'إضافة مندوب',
            'save' => 'حفظ',
            'cancel' => 'إلغاء',
            'assign' => 'تعيين'
        ],
        'messages' => [
            'created' => 'تم إنشاء المندوب بنجاح',
            'updated' => 'تم تحديث المندوب بنجاح',
            'deleted' => 'تم حذف المندوب بنجاح',
            'assigned' => 'تم تعيين المندوب بنجاح',
            'unassigned' => 'تم إلغاء تعيين المندوب بنجاح'
        ],
        'validation' => [
            'representative_required' => 'يجب اختيار مندوب',
            'order_required' => 'يجب اختيار طلب'
        ]
    ],
    'settings' => [
        'title' => 'إدارة الإعدادات',
        'index' => 'قائمة الإعدادات',
        'edit' => 'تعديل الإعدادات',
        'table_headers' => [
            'key' => 'المفتاح',
            'value' => 'القيمة',
            'group' => 'المجموعة',
            'actions' => 'إجراءات'
        ],
        'buttons' => [
            'save' => 'حفظ',
            'cancel' => 'إلغاء'
        ],
        'brand_name' => 'اسم العلامة التجارية',
        'logo_path' => 'مسار الشعار',
        'description' => 'الوصف',
        'primary_color' => 'اللون الأساسي',
        'secondary_color' => 'اللون الثانوي',
        'contact_emails' => 'بريد التواصل',
        'contact_phones' => 'أرقام التواصل',
        'address' => 'العنوان',
        'latitude' => 'خط العرض',
        'longitude' => 'خط الطول',
        'messages' => [
            'updated' => 'تم تحديث الإعدادات بنجاح'
        ],
        'brand_settings' => 'إعدادات العلامة التجارية',
        'theme_settings' => 'إعدادات السمة',
        'contact_information' => 'معلومات التواصل',
        'social_media' => 'وسائل التواصل الاجتماعي',
        'business_settings' => 'إعدادات الأعمال',
        'currency_name_en' => 'اسم العملة (إنجليزي)',
        'currency_name_ar' => 'اسم العملة (عربي)',
        'currency_symbol_en' => 'SAR',
        'currency_symbol_ar' => 'ر.س',
        'currency_symbol' => "ر.س",
        'tax_rate' => 'معدل الضريبة',
        'default_payment_terms' => 'شروط الدفع الافتراضية'
    ],
    'promotions' => [
        'title' => 'إدارة العروض',
        'create' => 'إضافة عرض',
        'table_headers' => [
            'id' => 'الرقم',
            'code' => 'الكود',
            'description' => 'الوصف',
            'discount_type' => 'نوع الخصم',
            'discount' => 'الخصم',
            'min_order' => 'الحد الأدنى للطلب',
            'max_discount' => 'الحد الأقصى للخصم',
            'usage_limit' => 'حد الاستخدام',
            'used' => 'مستخدم',
            'start_date' => 'تاريخ البدء',
            'end_date' => 'تاريخ الانتهاء',
            'status' => 'الحالة',
            'actions' => 'إجراءات',
        ],
        'filters' => [
            'search' => 'بحث...',
            'all_statuses' => 'جميع الحالات',
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'reset' => 'إعادة تعيين'
        ],
        'datatable' => [
            'empty' => 'لا توجد بيانات متاحة',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'info_empty' => 'عرض 0 إلى 0 من 0 مدخل',
            'info_filtered' => '(منتقاة من مجموع _MAX_ مدخل)',
            'length_menu' => 'عرض _MENU_ مدخل',
            'loading' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zero_records' => 'لا توجد سجلات مطابقة',
            'show_entries' => 'عرض _MENU_ مدخل',
            'search_label' => 'بحث:',
            'showing_entries' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'show' => 'عرض',
            'entries' => 'مدخلات',
            'info_text' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل'
        ]
    ],
    'banners' => [
        'title' => 'إدارة اللافتات',
        'index' => 'قائمة اللافتات',
        'create' => 'إنشاء لافتة',
        'edit' => 'تعديل لافتة',
        'table_headers' => [
            'id' => 'الرقم',
            'title' => 'العنوان',
            'image' => 'الصورة',
            'link' => 'الرابط',
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'buttons' => [
            'add' => 'إضافة لافتة',
            'save' => 'حفظ',
            'cancel' => 'إلغاء'
        ],
        'messages' => [
            'created' => 'تم إنشاء اللافتة بنجاح',
            'updated' => 'تم تحديث اللافتة بنجاح',
            'deleted' => 'تم حذف اللافتة بنجاح',
            'delete_confirm' => 'هل أنت متأكد أنك تريد حذف هذه اللافتة؟'
        ]
    ],
    'areas' => [
        'title' => 'إدارة المناطق',
        'edit' => 'تعديل المنطقة',
        'index' => 'قائمة المناطق',
        'create' => 'إنشاء منطقة',
        'edit' => 'تعديل منطقة',
        'table_headers' => [
            'id' => 'الرقم',
            'name_en' => 'الاسم (إنجليزي)',
            'name_ar' => 'الاسم (عربي)',
            'city' => 'المدينة',
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء',
            'code' => 'كود المنظقة',
            'actions' => 'إجراءات',
            'update' => 'تحديث',
            'cancel' => 'الغاء',
            'edit' => 'تحديث'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'buttons' => [
            'add' => 'إضافة منطقة',
            'reset' => 'إعادة تعيين',
            'save' => 'حفظ',
            'cancel' => 'إلغاء'
        ],
        'filters' => [
            'all_cities' => 'جميع المدن',
            'all_statuses' => 'جميع الحالات'
        ],
        'messages' => [
            'created' => 'تم إنشاء المنطقة بنجاح',
            'updated' => 'تم تحديث المنطقة بنجاح',
            'deleted' => 'تم حذف المنطقة بنجاح'
        ],
        'datatable' => [
            'processing' => 'جاري المعالجة...',
            'emptyTable' => 'لا توجد مناطق',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'infoEmpty' => 'عرض 0 إلى 0 من 0 مدخل',
            'infoFiltered' => '(منتقاة من مجموع _MAX_ مدخل)',
            'lengthMenu' => 'عرض _MENU_ مدخل',
            'loadingRecords' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zeroRecords' => 'لا توجد سجلات مطابقة'
        ],
        'search_placeholder' => 'ابحث عن مناطق...'
    ],
    'orders' => [
        'title' => 'الطلبات',
        'my_orders' => 'طلباتي',
        'sections' => [
            'details' => 'تفاصيل الطلب',
            'store_info' => 'معلومات المتجر',
            'order_info' => 'معلومات الطلب',
            'sub_orders' => 'الطلبات الفرعية',
            'actions' => 'الإجراءات',
            'timeline' => 'سجل الطلب'
        ],
        'labels' => [
            'store' => 'المتجر',
            'contact' => 'جهة الاتصال',
            'phone' => 'الهاتف',
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء',
            'total' => 'الإجمالي',
            'payment_due' => 'تاريخ الاستحقاق',
            'products_count' => 'عدد المنتجات',
            'verification' => 'التحقق',
            'cancellation_reason' => 'سبب الإلغاء'
        ],
        'order_details' => 'تفاصيل الطلب',
        'back_to_orders' => 'رجوع إلى الطلبات',
        'store_information' => 'معلومات المتجر',
        'order_information' => 'معلومات الطلب',
        'sub_orders' => 'الطلبات الفرعية',
        'order_timeline' => 'سجل الطلب',
        'download_invoice' => 'تحميل الفاتورة',
        'supplier' => 'المورد',
        'total' => 'الإجمالي',
        'payment_status' => 'حالة الدفع',
        'delivery_date' => 'تاريخ التسليم',
        'items' => 'العناصر',
        'actions' => 'الإجراءات',
        'paid' => 'مدفوع',
        'pending' => 'قيد الانتظار',
        'search_placeholder' => 'بحث...',
        'status' => [
            'all' => 'جميع الحالات',
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ],
        'table_headers' => [
            'id' => 'رقم',
            'store' => 'المتجر',
            'total' => 'المجموع',
            'status' => 'الحالة',
            'date' => 'التاريخ',
            'actions' => 'إجراءات',
            'supplier' => 'المورد',
            'sub_orders' => 'الطلبات الفرعية',
            'payment_method' => 'طريقة الدفع',
            'payment_status' => 'حالة الدفع',
            'delivery_date' => 'تاريخ التسليم',
            'items' => 'العناصر',
            'shipping_address' => 'عنوان الدفع',



        ],
        'reset' => 'إعادة تعيين',
        'details' => [
            'title' => 'تفاصيل الطلب #:number',
            'suborder_info' => 'معلومات الطلب الفرعي',
            'reference_number' => 'الرقم المرجعي',
            'created_at' => 'تاريخ الإنشاء',
            'updated_at' => 'تاريخ التحديث',
            'total_amount' => 'المبلغ الإجمالي',
            'store_info' => 'المتجر والفرع',
            'store' => 'المتجر',
            'branch' => 'الفرع',
            'status' => 'الحالة',
            'date' => 'التاريخ',
            'rejection_reason' => 'سبب الرفض',
            'delivered_at' => 'تاريخ التسليم',
            'not_delivered' => 'لم يتم التسليم',
            'products' => 'المنتجات',
            'no_products' => 'لا توجد منتجات لهذا الطلب',
            'image' => 'الصورة',
            'sku' => 'SKU',
            'product' => 'المنتج',
            'quantity' => 'الكمية',
            'price' => 'سعر الوحدة',
            'total' => 'الإجمالي',
            'weight' => 'الوزن',
            'dimensions' => 'الأبعاد',
            'description' => 'الوصف',
            'contact_phone' => 'هاتف الاتصال',
            'contact_email' => 'البريد الإلكتروني',
            'address' => 'العنوان',
            'location' => 'الموقع',
            'view_on_map' => 'عرض على الخريطة',
            'actions_title' => 'إجراءات الطلب',
            'actions' => [
                'accept_by_supplier' => 'قبول الطلب',
                'reject_by_supplier' => 'رفض الطلب',
                'deliver' => 'تم التوصيل',
                'cancel' => 'إلغاء',
                'confirm_rejection' => 'تأكيد الرفض',
                'assign_representative' => 'تعيين مندوب'
            ],
            'modal' => [
                'reject_title' => 'رفض الطلب',
                'reason_label' => 'السبب',
                'assign_representative' => 'تعيين مندوب',
                'select_representative' => 'اختر مندوب',
                'select_representative_placeholder' => 'اختر مندوب من القائمة'
            ]
        ]
    ],
    'supplier_dashboard' => [
        'navigation' => [
            'products' => 'المنتجات',
            'dashboard' => 'لوحة التحكم',
            'orders' => 'الطلبات',
            'representatives' => 'المندوبين',
            'profile' => 'الملف الشخصي',
        ],


        // 'status' => [
        //     'active' => 'نشط',
        //     'pending' => 'قيد الانتظار',
        //     'completed' => 'مكتمل',
        //     'inactive' => 'غير مفعل',

        // ],
        // 'stats' => [
        //     'total' => 'الإجمالي'
        // ],

        'table' => [
            'order_id' => 'رقم الطلب',
            'date' => 'التاريخ',
            'status' => 'الحالة',
            'amount' => 'المبلغ',
            'action' => 'إجراء',
            'category' => 'الفئة',
            'sales' => 'المبيعات',
            'store' => 'المتجر',
            'product' => 'المنتج'
        ],
        'charts' => [
            'order_trends' => 'اتجاهات الطلبات',
            'sales_by_category' => 'المبيعات حسب الفئة',
            'payment_methods' => 'طرق الدفع'
        ],
        'profile' => [
            'title' => 'ملف المورد الشخصي',
            'edit_title' => 'تعديل ملف المورد',
            'payment_term' => 'فترة السداد (أيام)',
            'current_password' => 'كلمة المرور الحالية',
            'new_password' => 'كلمة المرور الجديدة',
            'confirm_password' => 'تأكيد كلمة المرور',
            'profile' => 'الملف الشخصي'
        ],
        'products' => [
            'page_title' => 'منتجاتي',
            'add_product' => 'إضافة منتج',
            'search_placeholder' => 'بحث...',
            'edit_product' => 'تعديل المنتج',
            'edit_product_info' => 'تعديل معلومات المنتج',
            'edit_product' => 'تعديل المنتج',


            'table_headers' => [
                'id' => 'الرقم',
                'image' => 'الصورة',
                'name_en' => 'الاسم (الإنجليزية)',
                'name_ar' => 'الاسم (العربية)',
                'sku' => 'SKU',
                'price' => 'السعر',
                'category' => 'الفئة',
                'status' => 'الحالة',
                'actions' => 'إجراءات'
            ],
            'reset' => 'إعادة تعيين'
        ],
        'title' => 'لوحة تحكم المورد',
        'stats' => [
            'revenue' => 'الإيرادات',
            'sub_orders' => 'الطلبات الفرعية',
            'products' => 'المنتجات',
            'orders' => 'الطلبات',
            'representatives' => 'المندوبين',
            'total_representatives' => 'إجمالي المندوبين',
            'total' => 'المجموع الكلي',
        ],
        'status' => [
            'active' => 'نشط',
            'pending' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'inactive' => 'غير مفعل',
            'pending' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'active' => 'نشط'
        ],
        'tables' => [
            'recent_orders' => 'الطلبات الحديثة',
            'top_products' => 'أفضل المنتجات'
        ],
        'empty_states' => [
            'no_orders' => 'لا توجد طلبات حديثة',
            'no_products' => 'لا توجد منتجات'
        ],
        'buttons' => [
            'view_all' => 'مشاهدة الجميع'
        ]
    ],
    'store_orders' => [
        'title' => 'طلبات المتجر',
        'status' => [
            'all' => 'الكل',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
            'delivered' => 'تم التوصيل'
        ],
        'branches' => [
            'all' => 'جميع الفروع'
        ],
        'status' => [
            'all' => 'جميع الحالات',
            'approved' => 'تمت الموافقة',
            'rejected' => 'مرفوض',
            'delivered' => 'تم التوصيل'
        ],
        'buttons' => [
            'reset' => 'إعادة تعيين'
        ],
        'table_headers' => [
            'id' => 'الرقم',
            'branch' => 'الفرع',
            'due_date' => 'تاريخ الاستحقاق',
            'search' => 'بحث...',
            'supplier' => 'المورد',
            'status' => 'الحالة',
            'total' => 'الإجمالي',
            'date' => 'التاريخ',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'pending' => 'قيد الانتظار',
            'processing' => 'قيد المعالجة',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ],
        'buttons' => [
            'view' => 'عرض',
            'cancel' => 'إلغاء'
        ],
        'empty_state' => 'لا توجد طلبات'
    ],
    'store_dashboard' => [
        'title' => 'لوحة تحكم المتجر',
        'orders' => 'الطلبات',
        'recent_orders' => 'الطلبات الحديثة',
        'order_table' => [
            'number' => '#',
            'order_id' => 'رقم الطلب',
            'date' => 'التاريخ',
            'status' => 'الحالة',
            'amount' => 'المبلغ',
            'action' => 'إجراء',
            'no_orders' => 'لا توجد طلبات حديثة'
        ],
        'top_suppliers' => 'أفضل الموردين',
        'supplier_orders' => 'طلبات',
        'no_supplier_data' => 'لا توجد بيانات للموردين',
        'status' => [
            'completed' => 'مكتمل',
            'pending' => 'قيد الانتظار'
        ],
        'revenue' => 'الإيرادات',
        'order_trends' => 'اتجاهات الطلبات',
        'sales_by_category' => 'المبيعات حسب الفئة',
        'payment_methods' => 'طرق الدفع',
        'empty_states' => [
            'no_orders' => 'لا توجد طلبات حديثة',
            'no_suppliers' => 'لا توجد بيانات للموردين'
        ],
        'stats' => [
            'orders' => 'طلبات'
        ],
        'recent_orders' => 'الطلبات الحديثة',
        'order_table' => [
            'number' => '#',
            'order_id' => 'رقم الطلب',
            'date' => 'التاريخ',
            'status' => 'الحالة',
            'amount' => 'المبلغ',
            'action' => 'إجراء',
            'no_orders' => 'لا توجد طلبات حديثة'
        ],
        'top_suppliers' => 'أفضل الموردين',
        'supplier_orders' => 'طلبات',
        'no_supplier_data' => 'لا توجد بيانات للموردين',
        'status' => [
            'completed' => 'مكتمل',
            'pending' => 'قيد الانتظار'
        ]
    ],
    'store_menu' => [
        'dashboard' => 'لوحة التحكم',
        'orders' => 'الطلبات',
        'wallet' => 'المحفظة',
        'branches' => 'الفروع',
        'profile' => 'الملف الشخصي'
    ],
    // 'store_dashboard' => [

    // ],
    'wallet' => [
        'title' => 'محفظة المتجر',
        'balance' => [
            'current' => 'الرصيد الحالي',
            'credit_limit' => 'الحد الائتماني',
            'available_credit' => 'الائتمان المتاح'
        ],
        'actions' => [
            'charge' => 'شحن المحفظة',
            'view_transactions' => 'عرض جميع المعاملات',
            'quick_actions' => 'إجراءات سريعة',
            'create_order' => 'إنشاء طلب جديد'
        ],
        'transactions' => [
            'title' => 'المعاملات الحديثة',
            'search_transaction_id' => 'بحث بالرقم المرجعي',

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
            ],
            'empty' => 'لا توجد معاملات'
        ]
    ],
    'cities' => [
        'title' => 'إدارة المدن',
        'index' => 'قائمة المدن',
        'create' => 'إنشاء مدينة',
        'edit' => 'تعديل مدينة',
        'table_headers' => [
            'id' => 'الرقم',
            'name_en' => 'الاسم (إنجليزي)',
            'name_ar' => 'الاسم (عربي)',
            'code' => 'الكود',
            'areas_count' => 'عدد المناطق',
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات'
        ],
        'status' => [
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'buttons' => [
            'add' => 'إضافة مدينة',
            'reset' => 'إعادة تعيين',
            'save' => 'حفظ',
            'cancel' => 'إلغاء'
        ],
        'filters' => [
            'all_statuses' => 'جميع الحالات'
        ],
        'messages' => [
            'created' => 'تم إنشاء المدينة بنجاح',
            'updated' => 'تم تحديث المدينة بنجاح',
            'deleted' => 'تم حذف المدينة بنجاح'
        ],
        'datatable' => [
            'processing' => 'جاري المعالجة...',
            'emptyTable' => 'لا توجد مدن',
            'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
            'infoEmpty' => 'عرض 0 إلى 0 من 0 مدخل',
            'infoFiltered' => '(منتقاة من مجموع _MAX_ مدخل)',
            'lengthMenu' => 'عرض _MENU_ مدخل',
            'loadingRecords' => 'جاري التحميل...',
            'search' => 'بحث:',
            'zeroRecords' => 'لا توجد سجلات مطابقة'
        ],
        'search_placeholder' => 'ابحث عن مدن...'
    ],
    'transactions' => 'المعاملات',
    'common' => [
        'close' => 'إغلاق',
        'update' => 'تحديث',
        'save_changes' => 'حفظ التغيرات',
        'cancel' => "الغاء"
    ],

    'auto_verify_order' => 'التحقق اتوماتيكيا',
    // 'stores' => [
    //     'validation' => [
    //         'auto_verify' => 'حالة التحقق التلقائي'
    //     ]
    // ],
    'validation' => [
        'invalid_selection' => 'القيمة المختارة لـ :attribute غير صالحة. يجب أن تكون 0 أو 1.'
    ],

    'wallet' => [
        'transactions' => [
            'for_wallet' => 'معاملات المحفظة لـ #:id',
            'all' => 'كل معاملات المحفظة',
            'add_new' => 'إضافة جديد',
            'search_transaction_id' => 'بحث رقم المعاملة',
            'search_transaction_id' => 'بحث بالرقم المرجعي',
            'change_status' => 'تغير حالة العملية',
            'status_label' => 'حالة العملية'
        ],
        'transaction_status' => [
            'pending' => 'قيد الانتظار',
            'completed' => 'مكتمل',
            'failed' => 'فشل',
            'refunded' => 'تم الاسترداد'
        ],
        'transaction_types' => [
            'deposit' => 'إيداع',
            'payment' => 'دفع'
        ],
        'table' => [
            'id' => 'الرقم',
            'transaction_id' => 'رقم المعاملة',
            'wallet' => 'المحفظة',
            'amount' => 'المبلغ',
            'type' => 'النوع',
            'status' => 'الحالة',
            'created_at' => 'تاريخ الإنشاء',
            'actions' => 'إجراءات'
        ]
    ],

    'datatable' => [
        'empty' => 'لا توجد بيانات متاحة',
        'info' => 'عرض _START_ إلى _END_ من _TOTAL_ مدخل',
        'info_empty' => 'عرض 0 إلى 0 من 0 مدخل',
        'info_filtered' => '(منتقاة من مجموع _MAX_ مدخل)',
        'length_menu' => 'عرض _MENU_ مدخل',
        'loading' => 'جاري التحميل...',
        'processing' => 'جاري المعالجة...',
        'search' => 'بحث:',
        'zero_records' => 'لا توجد سجلات مطابقة',
        'first' => 'الأول',
        'last' => 'الأخير',
        'next' => 'التالي',
        'previous' => 'السابق'
    ],
    'select_category' => 'اختر الصنف',
    'edit' => 'تعديل',
    'edit_product' => 'تعديل المنتج',
    'select_date_range' => 'اختر نطاق التاريخ',
    'Manage Wallets' => 'ادارة المحافظ'
];
