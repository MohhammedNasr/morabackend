@php
    $statusClasses = [
        \App\Models\Order::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
        \App\Models\Order::STATUS_VERIFIED => 'bg-blue-100 text-blue-800',
        \App\Models\Order::STATUS_UNDER_PROCESSING => 'bg-purple-100 text-purple-800',
        \App\Models\Order::STATUS_COMPLETED => 'bg-green-100 text-green-800',
        \App\Models\Order::STATUS_CANCELED => 'bg-red-100 text-red-800'
    ];

    $statusText = [
        \App\Models\Order::STATUS_PENDING => __('orders.status.pending'),
        \App\Models\Order::STATUS_VERIFIED => __('orders.status.verified'),
        \App\Models\Order::STATUS_UNDER_PROCESSING => __('orders.status.under_processing'),
        \App\Models\Order::STATUS_COMPLETED => __('orders.status.completed'),
        \App\Models\Order::STATUS_CANCELED => __('orders.status.canceled')
    ];
@endphp

<span class="px-2 py-1 text-sm font-medium rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ $statusText[$order->status] ?? ucfirst($order->status) }}
</span>
