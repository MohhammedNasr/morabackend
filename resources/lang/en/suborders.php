<?php

return [
    'table_headers' => [
        'reference' => 'Reference Number',
        'supplier' => 'Supplier',
        'contact' => 'Contact Info',
        'status' => 'Status',
        'items' => 'Items',
        'unit_price' => 'Unit Price',
        'quantity' => 'Quantity',
        'total' => 'Total',
        'payment_status' => 'Payment Status',
        'delivery_status' => 'Delivery Status',
    ],
    'status' => [
        'pending' => 'Pending',
        'acceptedBySupplier' => 'Accepted by Supplier',
        'rejectedBySupplier' => 'Rejected by Supplier',
        'assignToRep' => 'Assigned to Representative',
        'rejectedByRep' => 'Rejected by Representative',
        'acceptedByRep' => 'Accepted by Representative',
        'modifiedBySupplier' => 'Modified by Supplier',
        'modifiedByRep' => 'Modified by Representative',
        'outForDelivery' => 'Out for Delivery',
        'delivered' => 'Delivered'
    ],
    'delivery' => [
        'delivered' => 'Delivered',
        'scheduled' => 'Scheduled',
        'not_set' => 'Not Set'
    ],
    'payment' => [
        'paid' => 'Paid',
        'pending' => 'Pending'
    ],
    'timeline' => [
        'payment_created' => 'Payment created',
        'payment_updated' => 'Payment updated',
        'payment_deleted' => 'Payment deleted',
        'status_changed' => 'Status changed to :status',
        'Payment phase 1' => 'Payment Phase 1',
        'Payment phase 2' => 'Payment Phase 2',
        'Payment phase 3' => 'Payment Phase 3',
        'Payment phase 4' => 'Payment Phase 4'
    ]
];
