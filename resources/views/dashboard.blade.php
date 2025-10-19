@extends('layouts.metronic.base')

@section('title', 'Dashboard')

@section('content')
<!-- begin:: Content -->
<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <!-- begin:: Dashboard Stats -->
    <div class="row">
        <div class="col-lg-3">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Orders
                        </h3>
                        <span class="kt-widget14__desc">
                            Total orders
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div class="kt-widget14__stat">{{ $totalOrders ?? 0 }}</div>
                            <canvas id="kt_chart_order_statistics" style="height: 140px; width: 140px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats">{{ $completedOrders ?? 0 }} Completed</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats">{{ $pendingOrders ?? 0 }} Pending</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-danger"></span>
                                <span class="kt-widget14__stats">{{ $cancelledOrders ?? 0 }} Cancelled</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Products
                        </h3>
                        <span class="kt-widget14__desc">
                            Total products
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div class="kt-widget14__stat">{{ $totalProducts ?? 0 }}</div>
                            <canvas id="kt_chart_product_statistics" style="height: 140px; width: 140px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-info"></span>
                                <span class="kt-widget14__stats">{{ $activeProducts ?? 0 }} Active</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-secondary"></span>
                                <span class="kt-widget14__stats">{{ $inactiveProducts ?? 0 }} Inactive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Revenue
                        </h3>
                        <span class="kt-widget14__desc">
                            Total revenue
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div class="kt-widget14__stat">{{ $totalRevenue ?? '$0' }}</div>
                            <canvas id="kt_chart_revenue_statistics" style="height: 140px; width: 140px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-brand"></span>
                                <span class="kt-widget14__stats">{{ $thisMonthRevenue ?? '$0' }} This Month</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-primary"></span>
                                <span class="kt-widget14__stats">{{ $lastMonthRevenue ?? '$0' }} Last Month</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-widget14">
                    <div class="kt-widget14__header">
                        <h3 class="kt-widget14__title">
                            Users
                        </h3>
                        <span class="kt-widget14__desc">
                            Total users
                        </span>
                    </div>
                    <div class="kt-widget14__content">
                        <div class="kt-widget14__chart">
                            <div class="kt-widget14__stat">{{ $totalUsers ?? 0 }}</div>
                            <canvas id="kt_chart_user_statistics" style="height: 140px; width: 140px;"></canvas>
                        </div>
                        <div class="kt-widget14__legends">
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-success"></span>
                                <span class="kt-widget14__stats">{{ $storeOwners ?? 0 }} Store Owners</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-warning"></span>
                                <span class="kt-widget14__stats">{{ $suppliers ?? 0 }} Suppliers</span>
                            </div>
                            <div class="kt-widget14__legend">
                                <span class="kt-widget14__bullet kt-bg-info"></span>
                                <span class="kt-widget14__stats">{{ $admins ?? 0 }} Admins</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Dashboard Stats -->

    <!-- begin:: Recent Orders -->
    <div class="row">
        <div class="col-xl-8">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Recent Orders
                        </h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="#" class="btn btn-label-brand btn-bold btn-sm">View All</a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget11">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td style="width:1%">#</td>
                                        <td style="width:40%">Order ID</td>
                                        <td style="width:14%">Date</td>
                                        <td style="width:15%">Status</td>
                                        <td style="width:15%">Amount</td>
                                        <td style="width:15%" class="kt-align-right">Action</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentOrders ?? [] as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->reference_number }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td><span class="kt-badge kt-badge--{{ $order->status_color }} kt-badge--inline kt-badge--pill">{{ $order->status }}</span></td>
                                        <td>{{ $order->total }}</td>
                                        <td class="kt-align-right"><a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md"><i class="la la-eye"></i></a></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No recent orders found</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="kt-portlet kt-portlet--height-fluid">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Top Products
                        </h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget5">
                        @forelse($topProducts ?? [] as $product)
                        <div class="kt-widget5__item">
                            <div class="kt-widget5__content">
                                <div class="kt-widget5__pic">
                                    <img class="kt-widget7__img" src="{{ $product->image_url ?? asset('metronic_theme/assets/media/products/product1.jpg') }}" alt="">
                                </div>
                                <div class="kt-widget5__section">
                                    <a href="#" class="kt-widget5__title">
                                        {{ $product->name }}
                                    </a>
                                    <p class="kt-widget5__desc">
                                        {{ Str::limit($product->description, 50) }}
                                    </p>
                                    <div class="kt-widget5__info">
                                        <span>Category:</span>
                                        <span class="kt-font-info">{{ $product->category->name ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-widget5__content">
                                <div class="kt-widget5__stats">
                                    <span class="kt-widget5__number">{{ $product->orders_count ?? 0 }}</span>
                                    <span class="kt-widget5__sales">sales</span>
                                </div>
                                <div class="kt-widget5__stats">
                                    <span class="kt-widget5__number">{{ $product->price ?? '$0' }}</span>
                                    <span class="kt-widget5__votes">price</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="kt-widget5__item">
                            <div class="kt-widget5__content">
                                <div class="kt-widget5__section">
                                    <p class="kt-widget5__desc text-center">
                                        No top products found
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Recent Orders -->
</div>
<!-- end:: Content -->
@endsection

@push('scripts')
<script src="{{ asset('metronic_theme/assets/vendors/custom/flot/flot.bundle.js') }}" type="text/javascript"></script>
<script src="{{ asset('metronic_theme/assets/vendors/custom/chart.js/dist/Chart.bundle.js') }}" type="text/javascript"></script>
<script>
    // Order Statistics Chart
    var orderCtx = document.getElementById('kt_chart_order_statistics').getContext('2d');
    var orderChart = new Chart(orderCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ $completedOrders ?? 65 }},
                    {{ $pendingOrders ?? 25 }},
                    {{ $cancelledOrders ?? 10 }}
                ],
                backgroundColor: [
                    KTApp.getStateColor('success'),
                    KTApp.getStateColor('warning'),
                    KTApp.getStateColor('danger')
                ]
            }],
            labels: [
                'Completed',
                'Pending',
                'Cancelled'
            ]
        },
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Order Statistics'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10,
                caretPadding: 0,
                displayColors: false,
                backgroundColor: KTApp.getStateColor('brand'),
                titleFontColor: '#ffffff',
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            }
        }
    });

    // Product Statistics Chart
    var productCtx = document.getElementById('kt_chart_product_statistics').getContext('2d');
    var productChart = new Chart(productCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ $activeProducts ?? 80 }},
                    {{ $inactiveProducts ?? 20 }}
                ],
                backgroundColor: [
                    KTApp.getStateColor('info'),
                    KTApp.getStateColor('secondary')
                ]
            }],
            labels: [
                'Active',
                'Inactive'
            ]
        },
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Product Statistics'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10,
                caretPadding: 0,
                displayColors: false,
                backgroundColor: KTApp.getStateColor('brand'),
                titleFontColor: '#ffffff',
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            }
        }
    });

    // Revenue Statistics Chart
    var revenueCtx = document.getElementById('kt_chart_revenue_statistics').getContext('2d');
    var revenueChart = new Chart(revenueCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ str_replace(['$', ','], '', $thisMonthRevenue ?? 70) }},
                    {{ str_replace(['$', ','], '', $lastMonthRevenue ?? 30) }}
                ],
                backgroundColor: [
                    KTApp.getStateColor('brand'),
                    KTApp.getStateColor('primary')
                ]
            }],
            labels: [
                'This Month',
                'Last Month'
            ]
        },
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'Revenue Statistics'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10,
                caretPadding: 0,
                displayColors: false,
                backgroundColor: KTApp.getStateColor('brand'),
                titleFontColor: '#ffffff',
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            }
        }
    });

    // User Statistics Chart
    var userCtx = document.getElementById('kt_chart_user_statistics').getContext('2d');
    var userChart = new Chart(userCtx, {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
                    {{ $storeOwners ?? 50 }},
                    {{ $suppliers ?? 30 }},
                    {{ $admins ?? 20 }}
                ],
                backgroundColor: [
                    KTApp.getStateColor('success'),
                    KTApp.getStateColor('warning'),
                    KTApp.getStateColor('info')
                ]
            }],
            labels: [
                'Store Owners',
                'Suppliers',
                'Admins'
            ]
        },
        options: {
            cutoutPercentage: 75,
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                display: false,
                position: 'top',
            },
            title: {
                display: false,
                text: 'User Statistics'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            },
            tooltips: {
                enabled: true,
                intersect: false,
                mode: 'nearest',
                bodySpacing: 5,
                yPadding: 10,
                xPadding: 10,
                caretPadding: 0,
                displayColors: false,
                backgroundColor: KTApp.getStateColor('brand'),
                titleFontColor: '#ffffff',
                cornerRadius: 4,
                footerSpacing: 0,
                titleSpacing: 0
            }
        }
    });
</script>
@endpush
