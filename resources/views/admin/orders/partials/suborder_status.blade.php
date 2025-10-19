@php
    $statusClasses = [
        \App\Models\SubOrder::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
        \App\Models\SubOrder::STATUS_ACCEPTED_BY_SUPPLIER => 'bg-blue-100 text-blue-800',
        \App\Models\SubOrder::STATUS_REJECTED_BY_SUPPLIER => 'bg-red-100 text-red-800',
        \App\Models\SubOrder::STATUS_ASSIGNED_TO_REP => 'bg-purple-100 text-purple-800',
        \App\Models\SubOrder::STATUS_REJECTED_BY_REP => 'bg-red-100 text-red-800',
        \App\Models\SubOrder::STATUS_ACCEPTED_BY_REP => 'bg-green-100 text-green-800',
        \App\Models\SubOrder::STATUS_MODIFIED_BY_SUPPLIER => 'bg-orange-100 text-orange-800',
        \App\Models\SubOrder::STATUS_MODIFIED_BY_REP => 'bg-orange-100 text-orange-800',
        \App\Models\SubOrder::STATUS_OUT_FOR_DELIVERY => 'bg-indigo-100 text-indigo-800',
        \App\Models\SubOrder::STATUS_DELIVERED => 'bg-green-100 text-green-800'
    ];
@endphp

<span class="px-2 py-1 text-sm font-medium rounded-full {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
    {{ __('suborders.status.' . $order->status) ?? ucfirst($order->status) }}
</span>
