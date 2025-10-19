@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.orders.title')</h3>
                <div class="kt-subheader__group">
                    {{-- <a href="{{ route('admin.orders.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.orders.buttons.add')
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <!--begin: Search Form -->
                <div class="kt-form kt-form--label-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }} kt-margin-b-10"
                    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="@lang('messages.search')"
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                            <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                <i class="la la-close"></i> @lang('messages.orders.buttons.reset')
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <style>
                        @if(app()->getLocale() == 'ar')
                        /* Force RTL on entire table container */
                        #orders_table_wrapper {
                            direction: rtl !important;
                        }

                        /* Force RTL on all table elements */
                        #orders_table_wrapper * {
                            direction: rtl !important;
                            text-align: right !important;
                        }

                        /* Fix DataTable specific elements */
                        #orders_table thead th,
                        #orders_table tbody td {
                            text-align: right !important;
                        }

                        /* Fix pagination position */
                        .dataTables_paginate {
                            float: left !important;
                        }

                        /* Fix filter input alignment */
                        .dataTables_filter input {
                            text-align: right !important;
                        }

                        /* Fix scroll container */
                        .dataTables_scrollHeadInner,
                        .dataTables_scrollFootInner {
                            width: 100% !important;
                            text-align: right !important;
                        }

                        /* Fix column sorting icons */
                        .sorting:after,
                        .sorting_asc:after,
                        .sorting_desc:after {
                            left: 5px !important;
                            right: auto !important;
                        }
                        @endif

                        .table-responsive {
                            position: relative;
                            z-index: 1;
                        }

                        #orders_table thead th {
                            position: sticky;
                            top: 0;
                            background: white;
                            z-index: 3;
                            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                        }

                        #orders_table thead th:first-child {
                            z-index: 4;
                        }

                        #orders_table tbody td {
                            position: relative;
                            z-index: 2;
                        }

                        /* Ensure table header stays fixed while scrolling */
                        .dataTables_scrollHead {
                            position: sticky;
                            top: 0;
                            z-index: 5;
                        }

                        /* Add smooth scrolling */
                        .dataTables_scrollBody {
                            overflow-y: auto !important;
                            scroll-behavior: smooth;
                        }

                        /* Add scrollbar styling */
                        .dataTables_scrollBody::-webkit-scrollbar {
                            width: 8px;
                            height: 8px;
                        }

                        .dataTables_scrollBody::-webkit-scrollbar-track {
                            background: #f1f1f1;
                        }

                        .dataTables_scrollBody::-webkit-scrollbar-thumb {
                            background: #888;
                            border-radius: 4px;
                        }

                        .dataTables_scrollBody::-webkit-scrollbar-thumb:hover {
                            background: #555;
                        }
                    </style>
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>@lang('messages.orders.table_headers.store')</label>
                                <select class="form-control kt-select2" id="store_id" name="store_id">
                                    <option value="">@lang('messages.all_stores')</option>
                                    @foreach ($stores as $store)
                                        <option value="{{ $store->id }}"
                                            {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                            {{ $store->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label>@lang('messages.orders.table_headers.supplier')</label>
                                <select class="form-control kt-select2" id="supplier_id" name="supplier_id">
                                    <option value="">@lang('messages.all_suppliers')</option>
                                    @isset($suppliers)
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} ({{ $supplier->code }})
                                            </option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>@lang('store.select_date_range')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control kt-datepicker" id="date_range"
                                        name="date_range" value="{{ request('date_range') }}"
                                        placeholder="@lang('store.select_date_range')">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-calendar-check-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>@lang('messages.orders.table_headers.status')</label>
                                <select class="form-control kt-select2" id="status" name="status">
                                    <option value="">@lang('store.all_statuses')</option>
                                    <option value="{{ \App\Models\Order::STATUS_PENDING }}" {{ request('status') == \App\Models\Order::STATUS_PENDING ? 'selected' : '' }}>
                                        @lang('orders.status.pending')</option>
                                    <option value="{{ \App\Models\Order::STATUS_VERIFIED }}" {{ request('status') == \App\Models\Order::STATUS_VERIFIED ? 'selected' : '' }}>
                                        @lang('orders.status.verified')</option>
                                    <option value="{{ \App\Models\Order::STATUS_UNDER_PROCESSING }}" {{ request('status') == \App\Models\Order::STATUS_UNDER_PROCESSING ? 'selected' : '' }}>
                                        @lang('orders.status.under_processing')</option>
                                    <option value="{{ \App\Models\Order::STATUS_COMPLETED }}" {{ request('status') == \App\Models\Order::STATUS_COMPLETED ? 'selected' : '' }}>
                                        @lang('orders.status.completed')</option>
                                    <option value="{{ \App\Models\Order::STATUS_CANCELED }}" {{ request('status') == \App\Models\Order::STATUS_CANCELED ? 'selected' : '' }}>
                                        @lang('orders.status.canceled')</option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="col-lg-3">
                            <div class="form-group">
                                <label>@lang('messages.orders.table_headers.payment_method')</label>
                                <select class="form-control kt-select2" id="payment_method" name="payment_method">
                                    <option value="">@lang('messages.orders.filters.all_methods')</option>
                                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>
                                        @lang('messages.orders.payment_method.cash')</option>
                                    <option value="credit_card"
                                        {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                        @lang('messages.orders.payment_method.credit_card')</option>
                                    <option value="bank_transfer"
                                        {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                        @lang('messages.orders.payment_method.bank_transfer')</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-3">
                            <div class="form-group">
                                <label>{{ __('messages.orders.shipping_method_label') }}</label>
                                <select class="form-control kt-select2" id="shipping_method" name="shipping_method">
                                    <option value="">@lang('messages.orders.filters.all_methods')</option>
                                    <option value="standard"
                                        {{ request('shipping_method') == 'standard' ? 'selected' : '' }}>@lang('messages.orders.shipping_method.standard')
                                    </option>
                                    <option value="express"
                                        {{ request('shipping_method') == 'express' ? 'selected' : '' }}>@lang('messages.orders.shipping_method.express')
                                    </option>
                                    <option value="pickup" {{ request('shipping_method') == 'pickup' ? 'selected' : '' }}>
                                        @lang('messages.orders.shipping_method.pickup')</option>
                                </select>
                            </div>
                        </div> --}}
                        {{-- <div class="col-lg-3">
                            <div class="form-group">
                                <label>@lang('messages.orders.order_type_label')</label>
                                <select class="form-control kt-select2" id="order_type" name="order_type">
                                    <option value="">@lang('messages.orders.filters.all_types')</option>
                                    <option value="regular" {{ request('order_type') == 'regular' ? 'selected' : '' }}>
                                        @lang('messages.orders.order_types.regular')</option>
                                    <option value="express" {{ request('order_type') == 'express' ? 'selected' : '' }}>
                                        @lang('messages.orders.order_types.express')</option>
                                    <option value="bulk" {{ request('order_type') == 'bulk' ? 'selected' : '' }}>
                                        @lang('messages.orders.order_types.bulk')</option>
                                </select>
                            </div>
                        </div> --}}
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary btn-brand--icon" type="submit">
                                        <span>
                                            @if (app()->getLocale() == 'ar')
                                                <span>@lang('messages.filter')</span>
                                                <i class="la la-search"></i>
                                            @else
                                                <i class="la la-search"></i>
                                                <span>@lang('messages.filter')</span>
                                            @endif
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <!-- end:: Filters -->

            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <!--begin: Datatable -->
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="orders_table">
                        <thead>
                            <tr>
                                <th>@lang('messages.orders.table_headers.id')</th>
                                <th>@lang('messages.orders.table_headers.reference')</th>
                                <th>@lang('messages.orders.table_headers.store')</th>
                                <th>@lang('messages.orders.table_headers.store_branch')</th>
                                <th>@lang('messages.orders.table_headers.supplier')</th>
                                <th>@lang('messages.orders.table_headers.status')</th>
                                <th>@lang('messages.orders.table_headers.sub_total')</th>
                                <th>@lang('messages.orders.table_headers.discount')</th>
                                <th>@lang('messages.orders.table_headers.promotion')</th>
                                <th>@lang('messages.orders.table_headers.total')</th>
                                <th>@lang('messages.orders.table_headers.sub_orders')</th>
                                <th>@lang('messages.orders.table_headers.payment_method')</th>
                                <th>@lang('messages.orders.table_headers.payment_status')</th>
                                <th>@lang('messages.orders.table_headers.shipping_address')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: right;">
                            <!-- DataTable will populate rows here -->
                        </tbody>
                    </table>
                    <!--end: Datatable -->
                </div>
            </div>
        </div>
        <!-- end:: Content -->
    @endsection

    @push('styles')
        <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}"
            rel="stylesheet" type="text/css" />
    @endpush

    @push('scripts')
        <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
            type="text/javascript"></script>
        <script>
            "use strict";
            $(document).ready(function() {
            var table = $('#orders_table').DataTable({
                processing: true,
                serverSide: true,
                language: {
                    url: "{{ asset('metronic_theme/assets/vendors/custom/datatables/ar.json') }}"
                },
                @if(app()->getLocale() == 'ar')
                dom: "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-4'i><'col-sm-4 text-center'l><'col-sm-4'p>>",
                columnDefs: [{
                    targets: '_all',
                    className: 'text-right'
                }],
                language: {
                    url: "{{ asset('metronic_theme/assets/vendors/custom/datatables/ar.json') }}"
                },
                @endif
                    ajax: {
                        url: "{{ route('admin.orders.datatable') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(d) {
                            return $.extend({}, d, {
                                store_id: $('#store_id').val(),
                                supplier_id: $('#supplier_id').val(),
                                status: $('#status').val(),
                                payment_method: $('#payment_method').val(),
                                shipping_method: $('#shipping_method').val(),
                                order_type: $('#order_type').val(),
                                date_range: $('#date_range').val()
                            });
                        }
                    },
                    columns: [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'reference_number',
                            name: 'reference_number'
                        },
                        {
                            data: 'store',
                            name: 'store'
                        },
                        {
                            data: 'store_branch',
                            name: 'store_branch',
                            render: function(data) {
                                return data ? data.name : '@lang('messages.not_available')';
                            }
                        },
                        {
                            data: 'suppliers',
                            name: 'suppliers',
                            render: function(data) {
                                if (!data || data.length === 0) return '@lang('messages.not_available')';
                                return data.map(s => s.name).join(', ');
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data) {
                                const statusClasses = {
                                    '{{ \App\Models\Order::STATUS_PENDING }}': 'warning',
                                    '{{ \App\Models\Order::STATUS_VERIFIED }}': 'info',
                                    '{{ \App\Models\Order::STATUS_UNDER_PROCESSING }}': 'primary',
                                    '{{ \App\Models\Order::STATUS_COMPLETED }}': 'success',
                                    '{{ \App\Models\Order::STATUS_CANCELED }}': 'danger'
                                };
                                const statusText = {
                                    '{{ \App\Models\Order::STATUS_PENDING }}': '@lang("orders.status.pending")',
                                    '{{ \App\Models\Order::STATUS_VERIFIED }}': '@lang("orders.status.verified")',
                                    '{{ \App\Models\Order::STATUS_UNDER_PROCESSING }}': '@lang("orders.status.under_processing")',
                                    '{{ \App\Models\Order::STATUS_COMPLETED }}': '@lang("orders.status.completed")',
                                    '{{ \App\Models\Order::STATUS_CANCELED }}': '@lang("orders.status.canceled")'
                                };
                                return data ?
                                    `<span class="kt-badge kt-badge--${statusClasses[data] || 'secondary'}">${statusText[data] || data}</span>` :
                                    '';
                            }
                        },
                        {
                            data: 'sub_total',
                            name: 'sub_total',
                            render: function(data) {
                                return data ? `@lang('messages.currency_symbol')${data.toFixed(2)}` : '';
                            }
                        },
                        {
                            data: 'discount',
                            name: 'discount',
                            render: function(data) {
                                return data ? `@lang('messages.currency_symbol')${data.toFixed(2)}` : '';
                            }
                        },
                        {
                            data: 'promotion',
                            name: 'promotion',
                            render: function(data) {
                                return data ? data.name : '@lang('messages.not_available')';
                            }
                        },
                        {
                            data: 'total_price',
                            name: 'total_price',
                            render: function(data) {
                                return data ? `@lang('messages.currency_symbol')${data.toFixed(2)}` : '';
                            }
                        },
                        {
                            data: 'sub_orders_count',
                            name: 'sub_orders_count',
                            render: function(data) {
                                return data || 0;
                            }
                        },
                        {
                            data: 'payment_method',
                            name: 'payment_method',
                            render: function(data) {
                                return data ? data : '@lang('messages.not_available')';
                            }
                        },
                        {
                            data: 'payment_status',
                            name: 'payment_status',
                            render: function(data) {
                                const statusClasses = {
                                    'paid': 'success',
                                    'pending': 'warning',
                                    'failed': 'danger'
                                };
                                const statusText = {
                                    'paid': '@lang("orders.payment_status.paid")',
                                    'pending': '@lang("orders.payment_status.pending")',
                                    'failed': '@lang("orders.payment_status.failed")'
                                };
                                return data ?
                                    `<span class="kt-badge kt-badge--${statusClasses[data] || 'secondary'}">${statusText[data] || data}</span>` :
                                    '';
                            }
                        },
                        {
                            data: 'shipping_address',
                            name: 'shipping_address',
                            render: function(data) {
                                return data ? data : '@lang('messages.not_available')';
                            }
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function(data) {
                                return data ? new Date(data).toLocaleDateString() : '';
                            }
                        },
                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                return data;
                            }
                        }
                    ],
                    scrollX: true,
                    scrollY: '600px',
                    scrollCollapse: true,
                    fixedHeader: true,
                    order: [
                        [0, 'desc']
                    ],
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    language: {
                        url: "{{ asset('metronic_theme/assets/vendors/custom/datatables/ar.json') }}"
                    }
                });

                $('#store_id, #supplier_id, #status, #payment_method, #shipping_method, #order_type').on('change',
                    function() {
                        table.draw();
                    });

                $('#date_range').daterangepicker({
                    autoUpdateInput: false,
                    locale: {
                        cancelLabel: '@lang('messages.clear')',
                        format: 'YYYY-MM-DD',
                        direction: '{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}',
                        applyLabel: '@lang('messages.apply')',
                        daysOfWeek: [
                            '@lang('messages.weekdays.sun')',
                            '@lang('messages.weekdays.mon')',
                            '@lang('messages.weekdays.tue')',
                            '@lang('messages.weekdays.wed')',
                            '@lang('messages.weekdays.thu')',
                            '@lang('messages.weekdays.fri')',
                            '@lang('messages.weekdays.sat')'
                        ],
                        monthNames: [
                            '@lang('messages.months.jan')',
                            '@lang('messages.months.feb')',
                            '@lang('messages.months.mar')',
                            '@lang('messages.months.apr')',
                            '@lang('messages.months.may')',
                            '@lang('messages.months.jun')',
                            '@lang('messages.months.jul')',
                            '@lang('messages.months.aug')',
                            '@lang('messages.months.sep')',
                            '@lang('messages.months.oct')',
                            '@lang('messages.months.nov')',
                            '@lang('messages.months.dec')'
                        ],
                        firstDay: {{ app()->getLocale() == 'ar' ? 6 : 0 }}
                    }
                }).on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                        'YYYY-MM-DD'));
                    table.draw();
                }).on('cancel.daterangepicker', function(ev, picker) {
                    $(this).val('');
                    table.draw();
                });

                $('#kt_reset').on('click', function(e) {
                    e.preventDefault();
                    $('#store_id, #supplier_id, #status, #payment_method, #shipping_method, #order_type').val(
                        '');
                    $('#date_range').val('');
                    table.search('').columns().search('').draw();
                    $(this).addClass('kt-hidden');
                });

                $('#store_id, #supplier_id, #status, #payment_method, #shipping_method, #order_type, #date_range').on(
                    'input change',
                    function() {
                        $('#kt_reset').removeClass('kt-hidden');
                    });

                // Handle status change
                $(document).on('click', '.change-status-btn', function() {
                    const button = $(this);
                    const url = button.data('url');
                    const newStatus = button.data('status');

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            status: newStatus
                        },
                        beforeSend: function() {
                            button.prop('disabled', true);
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                // Update status badge in table
                                const statusClasses = {
                                    'pending': 'warning',
                                    'verified': 'info',
                                    'under_processing': 'primary',
                                    'completed': 'success',
                                    'canceled': 'danger'
                                };
                                const statusTranslations = {
                                    'pending': '@lang("orders.status.pending")',
                                    'verified': '@lang("orders.status.verified")',
                                    'under_processing': '@lang("orders.status.under_processing")',
                                    'completed': '@lang("orders.status.completed")',
                                    'canceled': '@lang("orders.status.canceled")'
                                };
                                const row = button.closest('tr');
                                row.find('.kt-badge')
                                    .removeClass('kt-badge--warning kt-badge--info kt-badge--primary kt-badge--success kt-badge--danger')
                                    .addClass('kt-badge--' + (statusClasses[newStatus] || 'secondary'))
                                    .text(statusTranslations[newStatus] || newStatus);

                                // Show success toast
                                toastr.success(response.message);
                            }
                        },
                        error: function(xhr) {
                            toastr.error('Failed to update status');
                        },
                        complete: function() {
                            button.prop('disabled', false);
                        }
                    });
                });
            });
        </script>
    @endpush
