@extends('layouts.metronic.admin')

@section('title', __('messages.store_dashboard.title'))

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet" type="text/css" />
@endpush

@section('content')
    <div class="row kt-row kt-row-custom">
        <!-- Begin::Stats -->
        <div class="col-xl-3 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title" style="text-align: right">
                            @lang('store_dashboard.stats.orders')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="orderChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $completedOrders ?? 0 }} @lang('store_dashboard.status.completed')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $pendingOrders ?? 0 }} @lang('store_dashboard.status.pending')</span>
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
                            @lang('store_dashboard.stats.revenue')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="revenueChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-primary"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $totalRevenue ?? 0 }} @lang('store_dashboard.stats.total')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $pendingPayments ?? 0 }} @lang('store_dashboard.status.pending')</span>
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
                            @lang('store_dashboard.stats.customers')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="customerChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-brand"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $activeCustomers ?? 0 }} @lang('store_dashboard.status.active')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-danger"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $inactiveCustomers ?? 0 }} @lang('store_dashboard.status.inactive')</span>
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
                            @lang('store_dashboard.stats.products')
                        </h3>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <canvas id="productChart" style="height: 100px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $activeProducts ?? 0 }} @lang('store_dashboard.status.active')</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats" style="text-align: right">{{ $outOfStockProducts ?? 0 }} @lang('store_dashboard.status.out_of_stock')</span>
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
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('store_dashboard.charts.order_trends')</h3>
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
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('store_dashboard.charts.sales_by_category')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <canvas id="salesByCategoryChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <!-- End::Charts -->

        <!-- Begin::Recent Orders -->
        <div class="col-xl-8 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('store_dashboard.tables.recent_orders')</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('store.orders.index') }}" class="btn btn-label-brand btn-bold btn-sm">@lang('store_dashboard.buttons.view_all')</a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget11">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td style="text-align: right">@lang('store_dashboard.table_headers.order_number')</td>
                                        <td style="text-align: right">@lang('store_dashboard.table_headers.customer')</td>
                                        <td style="text-align: right">@lang('store_dashboard.table_headers.amount')</td>
                                        <td style="text-align: right">@lang('store_dashboard.table_headers.status')</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($recentOrders ?? [] as $order)
                                        <tr>
                                            <td style="text-align: right">{{ $order->reference_number }}</td>
                                            <td style="text-align: right">{{ $order->customer->name ?? 'N/A' }}</td>
                                            <td style="text-align: right">{{ $order->total_amount }}</td>
                                            <td style="text-align: right"><span
                                                    class="kt-badge kt-badge--{{ $order->status_color }} kt-badge--inline">@lang('orders.status.' . $order->status)</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">@lang('store_dashboard.empty_states.no_orders')</td>
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

        <!-- Begin::Top Suppliers -->
        <div class="col-xl-4 kt-col">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title kt-font-bold">@lang('store_dashboard.tables.top_suppliers')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget11">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td style="text-align: right">@lang('store_dashboard.table_headers.supplier')</td>
                                        <td style="text-align: right">@lang('store_dashboard.table_headers.orders')</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topSuppliers ?? [] as $supplier)
                                        <tr>
                                            <td style="text-align: right">{{ $supplier->name }}</td>
                                            <td style="text-align: right">{{ $supplier->total_orders }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">@lang('store_dashboard.empty_states.no_suppliers')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End::Top Suppliers -->
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
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

        // Revenue Chart
        const revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Total', 'Pending'],
                datasets: [{
                    data: [{{ $totalRevenue ?? 0 }}, {{ $pendingPayments ?? 0 }}],
                    backgroundColor: ['#5d78ff', '#ffb822']
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

        // Customer Chart
        const customerChart = new Chart(document.getElementById('customerChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [{{ $activeCustomers ?? 0 }}, {{ $inactiveCustomers ?? 0 }}],
                    backgroundColor: ['#5d78ff', '#fd397a']
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
                labels: ['Active', 'Out of Stock'],
                datasets: [{
                    data: [{{ $activeProducts ?? 0 }}, {{ $outOfStockProducts ?? 0 }}],
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

        // Sales by Category Chart
        const salesByCategoryChart = new Chart(document.getElementById('salesByCategoryChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($salesByCategoryLabels ?? []) !!},
                datasets: [{
                    label: 'Sales',
                    data: {!! json_encode($salesByCategoryData ?? []) !!},
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
