@extends('layouts.metronic.admin')

@section('title', __('messages.dashboard.title'))

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet" type="text/css" />
@endpush
{{-- @php
dd(app()->getLocale())
@endphp --}}
@section('content')
    <div class="row kt-row kt-row-custom">
        <!-- Begin::Stats -->
        <div class="col-xl-3 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title" style="text-align: right">
                            @lang('messages.dashboard.stats.stores')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="storeChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $verifiedStores ?? 0 }} @lang('messages.dashboard.status.verified')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $pendingStores ?? 0 }} @lang('messages.dashboard.status.pending')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title" style="text-align: right">
                            @lang('messages.dashboard.stats.suppliers')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="supplierChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $activeSuppliers ?? 0 }} @lang('messages.dashboard.status.active')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-danger"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $inactiveSuppliers ?? 0 }} @lang('messages.dashboard.status.inactive')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title" style="text-align: right">
                            @lang('messages.dashboard.stats.orders')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="orderChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $completedOrders ?? 0 }} @lang('messages.dashboard.status.completed')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $pendingOrders ?? 0 }} @lang('messages.dashboard.status.pending')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title" style="text-align: right">
                            @lang('messages.dashboard.stats.products')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="productChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-brand"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $activeProducts ?? 0 }} @lang('messages.dashboard.status.active')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::Stats -->

        <!-- Begin::Charts -->
        <div class="col-xl-8 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('messages.dashboard.charts.order_trends')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <canvas id="orderTrendChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('messages.dashboard.charts.store_performance')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <canvas id="storePerformanceChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- End::Charts -->

        <!-- Begin::Recent Orders -->
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            // Store Chart
            const storeChart = new Chart(document.getElementById('storeChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Verified', 'Pending'],
                    datasets: [{
                        data: [{{ $verifiedStores ?? 0 }}, {{ $pendingStores ?? 0 }}],
                        backgroundColor: ['#1dc9b7', '#ffb822']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Supplier Chart
            const supplierChart = new Chart(document.getElementById('supplierChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{
                        data: [{{ $activeSuppliers ?? 0 }}, {{ $inactiveSuppliers ?? 0 }}],
                        backgroundColor: ['#1dc9b7', '#fd397a']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Order Chart
            const orderChart = new Chart(document.getElementById('orderChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [{{ $completedOrders ?? 0 }}, {{ $pendingOrders ?? 0 }}],
                        backgroundColor: ['#1dc9b7', '#ffb822']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Product Chart
            const productChart = new Chart(document.getElementById('productChart').getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Active'],
                    datasets: [{
                        data: [{{ $activeProducts ?? 0 }}],
                        backgroundColor: ['#5d78ff']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Order Trend Chart
            const orderTrendChart = new Chart(document.getElementById('orderTrendChart').getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($orderTrendLabels ?? []) !!},
                    datasets: [{
                        label: 'Orders',
                        data: {!! json_encode($orderTrendData ?? []) !!},
                        backgroundColor: 'rgba(29, 201, 183, 0.1)',
                        borderColor: '#1dc9b7',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#1dc9b7'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Store Performance Chart
            const storePerformanceChart = new Chart(document.getElementById('storePerformanceChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($storePerformanceLabels ?? []) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($storePerformanceData ?? []) !!},
                        backgroundColor: '#5d78ff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
        @endpush
        <div class="col-xl-8 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('messages.dashboard.tables.recent_orders')</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-label-brand btn-bold btn-sm">@lang('messages.dashboard.buttons.view_all')</a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget11">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td style="text-align: right">@lang('messages.dashboard.table_headers.order_number')</td>
                                        <td style="text-align: right">@lang('messages.dashboard.table_headers.store')</td>
                                        <td style="text-align: right">@lang('messages.dashboard.table_headers.amount')</td>
                                        <td style="text-align: right">@lang('messages.dashboard.table_headers.status')</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders ?? [] as $order)
                                        <tr>
                                            <td style="text-align: right">{{ $order->reference_number }}</td>
                                            <td style="text-align: right">{{ $order->store->name }}</td>
                                            <td style="text-align: right">{{ $order->total_amount }}</td>
                                            <td style="text-align: right"><span
                                                    class="kt-badge kt-badge--{{ $order->status_color }} kt-badge--inline">@lang('orders.status.' . $order->status)</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">@lang('messages.dashboard.empty_states.no_orders')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::Recent Orders -->

        <!-- Begin::Recent Stores -->
        <div class="col-xl-4 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('messages.dashboard.tables.recent_stores')</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-label-brand btn-bold btn-sm">@lang('messages.dashboard.buttons.view_all')</a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget11">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td style="text-align: right">@lang('messages.dashboard.table_headers.store')</td>
                                        <td style="text-align: right">@lang('messages.dashboard.table_headers.status')</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentStores ?? [] as $store)
                                        <tr>
                                            <td style="text-align: right">{{ $store->name }}</td>
                                            <td style="text-align: right"><span
                                                    class="kt-badge kt-badge--{{ $store->is_verified ? 'success' : 'warning' }} kt-badge--inline">{{ $store->is_verified ? __('messages.dashboard.status.verified') : __('messages.dashboard.status.pending') }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">@lang('messages.dashboard.empty_states.no_stores')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::Recent Stores -->
    </div>
@endsection
