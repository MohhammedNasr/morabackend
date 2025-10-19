<?php

return [
    'Settings' => 'Settings',
    'Reports' => 'Reports',
    'About' => 'About',
    'Team' => 'Team',
    'Contact' => 'Contact',
    'not_available' => 'Not Available',
    'supplier_dashboard' => [
        'products' => [
            'edit_product' => 'Edit Product',
            'edit_product_info' => 'Edit Product Information'
        ]
    ],
    'store_menu' => [
        'dashboard' => 'Dashboard',
        'orders' => 'Orders',
        'wallet' => 'Wallet',
        'branches' => 'Branches',
        'profile' => 'Profile'
    ],
    'profile' => [
        'settings' => 'Profile Settings',
        'information' => 'Profile Information',
        'owner_name' => 'Owner Name',
        'store_name' => 'Store Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'commercial_record' => 'Commercial Record',
        'tax_record' => 'Tax Record',
        'credit_limit' => 'Credit Limit',
        'save_changes' => 'Save Changes',
        'phone_cannot_change' => 'Phone number cannot be changed',
        'commercial_cannot_change' => 'Commercial record cannot be changed',
        'credit_limit_managed' => 'Credit limit is managed by Mora administration'
    ],
    'dashboard' => [
        'title' => 'Admin Dashboard',
        'stats' => [
            'stores' => 'Total Stores',
            'suppliers' => 'Total Suppliers',
            'orders' => 'Total Orders',
            'products' => 'Total Products',
        ],
        'status' => [
            'verified' => 'Verified',
            'pending' => 'Pending',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'completed' => 'Completed'
        ],
        'charts' => [
            'order_trends' => 'Order Trends',
            'store_performance' => 'Store Performance'
        ],
        'tables' => [
            'recent_orders' => 'Recent Orders',
            'recent_stores' => 'Recent Stores'
        ],
        'buttons' => [
            'view_all' => 'View All'
        ],
        'table_headers' => [
            'order_number' => 'Order #',
            'store' => 'Store',
            'amount' => 'Amount',
            'status' => 'Status'
        ],
        'empty_states' => [
            'no_orders' => 'No recent orders',
            'no_stores' => 'No recent stores'
        ],
    ],
    'Dashboard' => 'Dashboard',
    'filter' => 'Filter',
    'select_date_range' => 'Select Date Range',
    'date' => 'Date',
    'actions' => 'Actions',
    'Admin Management' => 'Admin Management',
    'Users' => 'Users',
    'Admins' => 'Admins',
    'Stores' => 'Stores',
    'All Stores' => 'All Stores',
    'Suppliers' => 'Suppliers',
    'All Suppliers' => 'All Suppliers',
    'Products' => 'Products',
    'All Products' => 'All Products',
    'Categories' => 'Categories',
    'promotions' => [
        'title' => 'Promotions Management',
        'create' => 'Add Promotion',
        'all' => 'All Promotions',
        'table_headers' => [
            'id' => 'ID',
            'code' => 'Promo Code',
            'description' => 'Description',
            'discount_type' => 'Discount Type',
            'discount' => 'Discount',
            'min_order' => 'Minimum Order',
            'max_discount' => 'Max Discount',
            'usage_limit' => 'Usage Limit',
            'used' => 'Used',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'status' => 'Status',
            'actions' => 'Actions'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_statuses' => 'All Statuses',
            'all' => 'All',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'reset' => 'Reset'
        ],
        'messages' => [
            'created' => 'Promotion created successfully',
            'updated' => 'Promotion updated successfully',
            'deleted' => 'Promotion deleted successfully'
        ]
    ],
    'Promotions' => 'Promotions',
    'currency_symbol' => "SAR",
    'orders' => [
        'title' => 'Orders Management',
        'status' => [
            'pending' => 'Pending',
            'verified' => 'Verified',
            'under_processing' => 'Under Processing',
            'completed' => 'Completed',
            'canceled' => 'Canceled'
        ],
        'payment' => [
            'name' => 'Payment',
            'due_date' => 'Due Date',
            'amount' => 'Amount',
            'none_found' => 'No payments found'
        ],
        'shipping_method_label' => 'Shipping Method',
        'select_date_range_label' => 'Date Range',
        'index' => 'Orders List',
        'create' => 'Create Order',
        'show' => 'View Order',
        'date_range' => "Select Date Range",
        'order_type_label' => 'Order Type',
        'table_headers' => [
            'id' => 'ID',
            'store' => 'Store',
            'supplier' => 'Supplier',
            'status' => 'Status',
            'total' => 'Total',
            'sub_orders' => 'Sub Orders',
            'payment_method' => 'Payment Method',
            'shipping_address' => 'Shipping Address',
            'shipping_method' => 'Shipping Method',
            'date' => 'Date',
            'actions' => 'Actions'
        ],
        'status' => [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'delivered' => 'Delivered'
        ],
        'payment_method' => [
            'cash' => 'Cash',
            'credit_card' => 'Credit Card',
            'bank_transfer' => 'Bank Transfer'
        ],
        'shipping_method' => [
            'standard' => 'Standard',
            'express' => 'Express',
            'pickup' => 'Pickup'
        ],
        'order_type' => [
            'regular' => 'Regular',
            'express' => 'Express',
            'bulk' => 'Bulk'
        ],
        'buttons' => [
            'add' => 'Add Order',
            'filter' => 'Filter',
            'reset' => 'Reset'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_stores' => 'All Stores',
            'all_suppliers' => 'All Suppliers',
            'all_statuses' => 'All Statuses',
            'all_methods' => 'All Methods',
            'all_types' => 'All Types',
            'date_range' => 'Date Range'
        ],
        'messages' => [
            'created' => 'Order created successfully',
            'updated' => 'Order updated successfully',
            'deleted' => 'Order deleted successfully'
        ]
    ],
    'Orders' => 'Orders',
    'All Orders' => 'All Orders',
    'Sub Orders' => 'Sub Orders',
    'Finance' => 'Finance',
    'wallets' => [
        'title' => 'Wallets Management',
        'balance' => 'Balance',
        'transactions' => 'Transactions',
        'table_headers' => [
            'id' => 'ID',
            'user' => 'User',
            'owner' => 'Owner',
            'type' => 'Type',
            'amount' => 'Amount',
            'balance' => 'Balance',
            'description' => 'Description',
            'reference' => 'Reference',
            'status' => 'Status',
            'date' => 'Date',
            'last_transaction' => 'Last Transaction',
            'created_at' => 'Created At',
            'actions' => 'Actions'
        ],
        'types' => [
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'payment' => 'Payment',
            'refund' => 'Refund',
            'transfer' => 'Transfer'
        ],
        'statuses' => [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'reversed' => 'Reversed'
        ],
        'buttons' => [
            'add' => 'Add',
            'add_funds' => 'Add Funds',
            'withdraw' => 'Withdraw',
            'transfer' => 'Transfer',
            'filter' => 'Filter',
            'reset' => 'Reset'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_types' => 'All Types',
            'all_statuses' => 'All Statuses',
            'date_range' => 'Date Range'
        ],
        'messages' => [
            'deposit_success' => 'Funds deposited successfully',
            'withdrawal_success' => 'Withdrawal request submitted',
            'transfer_success' => 'Transfer completed successfully',
            'insufficient_funds' => 'Insufficient funds'
        ]
    ],
    'Wallets' => 'Wallets',
    'representatives' => [
        'title' => 'Representatives Management',
        'create' => 'Add Representative',
        'edit' => 'Edit Representative',
        'table_headers' => [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'status' => 'Status',
            'stores_count' => 'Assigned Stores',
            'created_at' => 'Created At',
            'actions' => 'Actions'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'on_leave' => 'On Leave'
        ],
        'buttons' => [
            'add' => 'Add Representative',
            'assign_stores' => 'Assign Stores',
            'filter' => 'Filter',
            'reset' => 'Reset'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_statuses' => 'All Statuses',
            'date_range' => 'Date Range'
        ],
        'messages' => [
            'created' => 'Representative created successfully',
            'updated' => 'Representative updated successfully',
            'deleted' => 'Representative deleted successfully',
            'stores_assigned' => 'Stores assigned successfully'
        ],
        'validation' => [
            'name' => 'Representative Name',
            'phone' => 'Phone Number',
            'email' => 'Email Address'
        ],
        'modal' => [
            'edit_title' => 'Edit Representative',
            'name' => 'Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'password' => 'Password',
            'password_placeholder' => 'Leave blank to keep current',
            'supplier' => 'Supplier',
            'close' => 'Close',
            'update' => 'Update'
        ]
    ],
    'Representatives' => 'Representatives',
    'All Representatives' => 'All Representatives',
    'Cities' => 'Cities',
    'Areas' => 'Areas',
    'banners' => [
        'title' => 'Banners Management',
        'table_headers' => [
            'id' => 'ID',
            'title' => 'Title',
            'image' => 'Image',
            'link' => 'Link',
            'status' => 'Status',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'actions' => 'Actions'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'buttons' => [
            'add' => 'Add Banner',
            'filter' => 'Filter',
            'reset' => 'Reset'
        ]
    ],
    'Banners' => 'Banners',
    'areas' => [
        'title' => 'Areas Management',
        'table_headers' => [
            'id' => 'ID',
            'name_en' => 'Name (English)',
            'name_ar' => 'Name (Arabic)',
            'city' => 'City',
            'status' => 'Status',
            'actions' => 'Actions'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'buttons' => [
            'add' => 'Add Area',
            'reset' => 'Reset',
            'save' => 'Save',
            'cancel' => 'Cancel'
        ],
        'filters' => [
            'all_cities' => 'All Cities',
            'all_statuses' => 'All Statuses'
        ],
        'messages' => [
            'created' => 'Area created successfully',
            'updated' => 'Area updated successfully',
            'deleted' => 'Area deleted successfully'
        ],
        'datatable' => [
            'processing' => 'Processing...',
            'emptyTable' => 'No areas found',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
            'infoEmpty' => 'Showing 0 to 0 of 0 entries',
            'infoFiltered' => '(filtered from _MAX_ total entries)',
            'lengthMenu' => 'Show _MENU_ entries',
            'loadingRecords' => 'Loading...',
            'search' => 'Search:',
            'zeroRecords' => 'No matching records found'
        ],
        'search_placeholder' => 'Search areas...'
    ],
    'products' => [
        'title' => 'Products Management',
        'index' => 'Products List',
        'create' => 'Create Product',
        'edit' => 'Edit Product',
        'show' => 'View Product',
        'delete' => 'Delete Product',
        'import' => 'Import Products',
        'buttons' => [
            'add' => 'Add Product',
            'save' => 'Save',
            'cancel' => 'Cancel',
            'import' => 'Import'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_statuses' => 'All Statuses',
            'all_categories' => 'All Categories'
        ],
        'table_headers' => [
            'id' => 'ID',
            'image' => 'Image',
            'name_en' => 'Name (English)',
            'name_ar' => 'Name (Arabic)',
            'sku' => 'SKU',
            'price' => 'Price',
            'category' => 'Category',
            'status' => 'Status',
            'actions' => 'Actions'
        ],
        'status' => [
            'active' => 'Active',
            'deleted' => 'Deleted'
        ],
        'messages' => [
            'created' => 'Product created successfully',
            'updated' => 'Product updated successfully',
            'deleted' => 'Product deleted successfully',
            'imported' => 'Products imported successfully'
        ],
        'validation' => [
            'name_en' => 'Name (English)',
            'name_ar' => 'Name (Arabic)',
            'price' => 'Price'
        ],
        'datatable' => [
            'processing' => 'Processing...',
            'emptyTable' => 'No products found',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ products',
            'infoEmpty' => 'Showing 0 to 0 of 0 products',
            'infoFiltered' => '(filtered from _MAX_ total products)',
            'lengthMenu' => 'Show _MENU_ products',
            'loadingRecords' => 'Loading...',
            'search' => 'Search:',
            'zeroRecords' => 'No matching products found'
        ]
    ],
    'categories' => [
        'title' => 'Categories Management',
        'create' => 'Create Category',
        'table_headers' => [
            'id' => 'ID',
            'image' => 'Image',
            'name_en' => 'Name (English)',
            'name_ar' => 'Name (Arabic)',
            'status' => 'Status',
            'products' => 'Products',
            'actions' => 'Actions'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_statuses' => 'All Statuses',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'reset' => 'Reset'
        ],
        'messages' => [
            'created' => 'Category created successfully',
            'updated' => 'Category updated successfully',
            'deleted' => 'Category deleted successfully'
        ]
    ],
    'users' => [
        'title' => 'Users Management',
        'buttons' => [
            'add' => 'Add User',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'save' => 'Save',
            'cancel' => 'Cancel',
            'filter' => 'Filter',
            'reset' => 'Reset'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_statuses' => 'All Statuses',
            'active' => 'Active',
            'inactive' => 'Inactive',
            'suspended' => 'Suspended'
        ],
        'table_headers' => [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'role' => 'Role',
            'roles' => 'Roles',
            'stores' => 'Stores',
            'status' => 'Status',
            'created_at' => 'Created At',
            'actions' => 'Actions'
        ],
        'datatable' => [
            'processing' => 'Processing...',
            'emptyTable' => 'No users found',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ users',
            'infoEmpty' => 'Showing 0 to 0 of 0 users',
            'infoFiltered' => '(filtered from _MAX_ total users)',
            'lengthMenu' => 'Show _MENU_ users',
            'loadingRecords' => 'Loading...',
            'search' => 'Search:',
            'zeroRecords' => 'No matching users found'
        ]
    ],
    'Settings' => 'Settings',
    'Reports' => 'Reports',
    'About' => 'About',
    'Team' => 'Team',
    'Contact' => 'Contact',
    'not_available' => 'Not Available',
    'notifications' => [
        'new_order' => 'New order received',
        'new_customer' => 'New customer registered',
        'time_ago' => ':time ago'
    ],
    'profile' => [
        'my_profile' => 'My Profile',
        'account_settings' => 'Account Settings & More',
        'sign_out' => 'Sign Out'
    ],
    'stores' => [
        'title' => 'Stores Management',
        'buttons' => [
            'add' => 'Add Store',
            'reset' => 'Reset'
        ],
        'filters' => [
            'search' => 'Search...',
            'status' => 'Status',
            'all_statuses' => 'All Statuses'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'table_headers' => [
            'id' => 'ID',
            'name' => 'Store Name',
            'owner_name' => 'Owner Name',
            'owner_email' => 'Owner Email',
            'phone' => 'Phone',
            'commercial_record' => 'Commercial Record',
            'credit_limit' => 'Credit Limit',
            'status' => 'Status',
            'orders' => 'Orders',
            'created_at' => 'Created At',
            'actions' => 'Actions',
            'Verified' => 'Verified',
            'Active' => 'Active'
        ],
        'messages' => [
            'created' => 'Store created successfully',
            'updated' => 'Store updated successfully',
            'deleted' => 'Store deleted successfully',
            'imported' => 'Stores imported successfully'
        ],
        'validation' => [
            'name' => 'Store Name',
            'description' => 'Description'
        ],
        'datatable' => [
            'processing' => 'Processing...',
            'emptyTable' => 'No data available',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
            'infoEmpty' => 'Showing 0 to 0 of 0 entries',
            'infoFiltered' => '(filtered from _MAX_ total entries)',
            'lengthMenu' => 'Show _MENU_ entries',
            'loadingRecords' => 'Loading...',
            'search' => 'Search:',
            'zeroRecords' => 'No matching records found'
        ]
    ],
    'suppliers' => [
        'title' => 'Suppliers Management',
        'buttons' => [
            'add' => 'Add Supplier',
            'reset' => 'Reset'
        ],
        'filters' => [
            'search' => 'Search...',
            'status' => 'Status',
            'all_statuses' => 'All Statuses'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'table_headers' => [
            'id' => 'ID',
            'name' => 'Supplier Name',
            'contact_name' => 'Contact Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'address' => 'Address',
            'commercial_record' => 'Commercial Record',
            'payment_terms' => 'Payment Terms',
            'status' => 'Status',
            'created_at' => 'Created At',
            'actions' => 'Actions'
        ],
        'datatable' => [
            'processing' => 'Processing...',
            'emptyTable' => 'No data available',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
            'infoEmpty' => 'Showing 0 to 0 of 0 entries',
            'infoFiltered' => '(filtered from _MAX_ total entries)',
            'lengthMenu' => 'Show _MENU_ entries',
            'loadingRecords' => 'Loading...',
            'search' => 'Search:',
            'zeroRecords' => 'No matching records found'
        ]
    ],
    'branches' => [
        'title' => 'Manage Branches',
        'create' => 'Add Branch',
        'edit' => 'Edit Branch',
        'table_headers' => [
            'id' => 'ID',
            'name' => 'Name',
            'main_name' => 'Main Name',
            'street' => 'Street',
            'building' => 'Building',
            'floor' => 'Floor',
            'phone' => 'Phone',
            'city' => 'City',
            'area' => 'Area',
            'coordinates' => 'Coordinates',
            'main_branch' => 'Main Branch',
            'status' => 'Status',
            'actions' => 'Actions'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive',
            'all' => 'All'
        ],
        'main_branch' => [
            'yes' => 'Yes',
            'no' => 'No'
        ],
        'datatable' => [
            'empty' => 'No branches found',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
            'info_empty' => 'Showing 0 to 0 of 0 entries',
            'search' => 'Search:',
            'processing' => 'Processing...',
            'length_menu' => 'Show _MENU_ entries'
        ],
        'address' => 'Address',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'notes' => 'Notes'
    ],
    'cities' => [
        'title' => 'Cities Management',
        'create' => 'Create City',
        'table_headers' => [
            'id' => 'ID',
            'name_en' => 'Name (English)',
            'name_ar' => 'Name (Arabic)',
            'status' => 'Status',
            'areas_count' => 'Areas',
            'actions' => 'Actions',
            'code' => 'Code'
        ],
        'status' => [
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'buttons' => [
            'add' => 'Add City',
            'save' => 'Save',
            'cancel' => 'Cancel'
        ],
        'filters' => [
            'search' => 'Search...',
            'all_statuses' => 'All Statuses'
        ],
        'messages' => [
            'created' => 'City created successfully',
            'updated' => 'City updated successfully',
            'deleted' => 'City deleted successfully'
        ],
        'search_placeholder' => 'Search cities...',
        'datatable' => [
            'processing' => 'Processing...',
            'emptyTable' => 'No cities found',
            'info' => 'Showing _START_ to _END_ of _TOTAL_ cities',
            'infoEmpty' => 'Showing 0 to 0 of 0 cities',
            'infoFiltered' => '(filtered from _MAX_ total cities)',
            'lengthMenu' => 'Show _MENU_ cities',
            'loadingRecords' => 'Loading...',
            'search' => 'Search:',
            'zeroRecords' => 'No matching cities found'
        ]
    ],
    'settings' => [
        'title' => 'Settings Management',
        'index' => 'Settings List',
        'edit' => 'Edit Settings',
        'table_headers' => [
            'key' => 'Key',
            'value' => 'Value',
            'group' => 'Group',
            'actions' => 'Actions'
        ],
        'buttons' => [
            'save' => 'Save',
            'cancel' => 'Cancel'
        ],
        'brand_name' => 'Mora',
        'logo_path' => 'Logo Path',
        'description' => 'Mora Platform',
        'primary_color' => 'Primary Color',
        'secondary_color' => 'Secondary Color',
        'contact_emails' => 'Contact Emails',
        'contact_phones' => 'Contact Phones',
        'address' => '123 Business St, City, Country',
        'latitude' => 'Latitude',
        'longitude' => 'Longitude',
        'copyright' => 'Copyright © :year Mora. All rights reserved.',
        'version' => 'Version :version',
        'contact_email' => 'contact@mora.com',
        'support_email' => 'support@mora.com',
        'phone' => '+1234567890',
        'messages' => [
            'updated' => 'Settings updated successfully'
        ]
    ],
    'transactions' => 'Transactions',
    'stores' => [
        'table_headers' => [
            'auto_verify' => 'Auto Verify Orders'
        ],
        'validation' => [
            'auto_verify' => 'auto verification status'
        ]
    ],
    'validation' => [
        'invalid_selection' => 'The selected :attribute is invalid.'
    ],

    'wallet' => [
        'transactions' => [
            'for_wallet' => 'Wallet Transactions for #:id',
            'all' => 'All Wallet Transactions',
            'add_new' => 'Add New',
            'search_transaction_id' => 'Search Transaction ID',
            'change_status' => 'Change Transaction Status',
            'status_label' => 'Transaction Status'
        ],
        'transaction_status' => [
            'pending' => 'Pending',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'reversed' => 'Reversed'
        ],
        'transaction_types' => [
            'deposit' => 'Deposit',
            'withdrawal' => 'Withdrawal',
            'payment' => 'Payment',
            'refund' => 'Refund',
            'transfer' => 'Transfer'
        ],
        'table' => [
            'id' => 'ID',
            'transaction_id' => 'Transaction ID',
            'wallet' => 'Wallet',
            'amount' => 'Amount',
            'type' => 'Type',
            'status' => 'Status',
            'created_at' => 'Created At',
            'actions' => 'Actions'
        ]
    ],

    'datatable' => [
        'empty' => 'No data available',
        'info' => 'Showing _START_ to _END_ of _TOTAL_ entries',
        'search' => 'Search:',
        'info_empty' => 'Showing 0 to 0 of 0 entries',
        'info_filtered' => '(filtered from _MAX_ total entries)',
        'length_menu' => 'Show _MENU_ entries',
        'loading' => 'Loading...',
        'processing' => 'Processing...',
        'search' => 'Search:',
        'zero_records' => 'No matching records found',
        'first' => 'First',
        'last' => 'Last',
        'next' => 'Next',
        'previous' => 'Previous'
    ],
    'select_category' => 'Select Category',
    'edit' => 'Edit',
    'edit_product' => 'Edit Product',
];
