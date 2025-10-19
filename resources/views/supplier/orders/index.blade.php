@extends('layouts.metronic.supplier')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.orders.my_orders')</h3>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('messages.orders.title')</h3>
        </div>
        <!-- end:: Content Head -->

        <!-- begin:: Content -->
        <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
            <div class="kt-portlet kt-portlet--mobile">
                <div class="kt-portlet__body">
                    <!--begin: Search Form -->
                    <div class="kt-form kt-form--label-right kt-margin-b-10">
                        <div class="row align-items-center">
                            <div class="col-xl-8 order-2 order-xl-1">
                                <div class="row align-items-center">
                                    <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                                        <div class="kt-input-icon kt-input-icon--left">
                                            <input type="text" class="form-control" placeholder="@lang('messages.orders.search_placeholder')"
                                                id="generalSearch">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                        <select class="form-control" id="statusFilter">
                                            <option value="">@lang('messages.orders.status.all')</option>
                                            <option value="pending">@lang('messages.orders.status.pending')</option>
                                            <option value="processing">@lang('messages.orders.status.processing')</option>
                                            <option value="completed">@lang('messages.orders.status.completed')</option>
                                            <option value="cancelled">@lang('messages.orders.status.cancelled')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                        <div class="kt-input-icon kt-input-icon--left">
                                            <input type="text" class="form-control" id="date-range-filter" placeholder="@lang('suborders.datatable.select_date_range')">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                <span><i class="la la-calendar"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                                <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                    <i class="la la-close"></i> @lang('messages.orders.reset')
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--end: Search Form -->

                    <!--begin: Datatable -->
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                        <table class="table table-striped- table-bordered table-hover table-checkable {{ app()->getLocale() === 'ar' ? 'rtl-table' : '' }}" id="orders-table">
                            <style>
                                #orders-table thead th {
                                    position: sticky;
                                    top: 0;
                                    background: white;
                                    z-index: 1;
                                    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                                }

                                #orders-table thead th:first-child {
                                    z-index: 2;
                                }
                            </style>
                            <thead>
                                <tr>
                                    <th>@lang('suborders.datatable.reference')</th>
                                    <th>@lang('messages.orders.table_headers.store')</th>
                                    <th>@lang('suborders.datatable.products')</th>
                                    <th>@lang('suborders.datatable.sub_total')</th>
                                    <th>@lang('suborders.datatable.discount')</th>
                                    <th>@lang('suborders.datatable.total_amount')</th>
                                    <th>@lang('suborders.datatable.status')</th>
                                    <th>@lang('suborders.datatable.date')</th>
                                    <th>@lang('suborders.datatable.actions')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .badge-light-success {
            min-width: fit-content;
            padding: 0.4em 0.8em;
            font-size: 100%;
            white-space: nowrap;
        }
        .rtl-table td,
        .rtl-table th,
        .rtl-table .dataTables_empty,
        .rtl-table .dataTables_info,
        .rtl-table .dataTables_length label,
        .rtl-table .dataTables_filter label,
        .rtl-table .dataTables_paginate .paginate_button {
            text-align: right;
            direction: rtl;
        }
        .rtl-table .dataTables_wrapper .dataTables_filter input {
            margin-left: 0;
            margin-right: 0.5em;
        }
    </style>
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var table = $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('supplier.orders.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            search: $('#generalSearch').val(),
                            status: $('#statusFilter').val(),
                            date_range: $('#date-range-filter').val()
                        });
                    }
                },
                columns: [
                    {
                        data: 'reference_number',
                        name: 'reference_number',
                        render: function(data) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'store',
                        name: 'store.name',
                        render: function(data) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'products_count',
                        name: 'products_count',
                        render: function(data) {
                            return data || 0;
                        }
                    },
                    {
                        data: 'sub_total',
                        name: 'sub_total',
                        render: function(data) {
                            const isArabic = '{{ app()->getLocale() }}' === 'ar';
                            const currencySymbol = isArabic ? '{{ config('settings.currency_symbol_ar', 'ر.س') }}' : '{{ config('settings.currency_symbol', '$') }}';
                            const formattedAmount = parseFloat(data || 0).toLocaleString('ar-EG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            return `${currencySymbol} ${formattedAmount}`;
                        }
                    },
                    {
                        data: 'discount',
                        name: 'discount',
                        render: function(data) {
                            const isArabic = '{{ app()->getLocale() }}' === 'ar';
                            const currencySymbol = isArabic ? '{{ config('settings.currency_symbol_ar', 'ر.س') }}' : '{{ config('settings.currency_symbol', '$') }}';
                            const formattedAmount = parseFloat(data || 0).toLocaleString('ar-EG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            return `${currencySymbol} ${formattedAmount}`;
                        }
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: function(data) {
                            const isArabic = '{{ app()->getLocale() }}' === 'ar';
                            const currencySymbol = isArabic ? '{{ config('settings.currency_symbol_ar', 'ر.س') }}' : '{{ config('settings.currency_symbol', '$') }}';
                            const formattedAmount = parseFloat(data || 0).toLocaleString('ar-EG', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            return `${currencySymbol} ${formattedAmount}`;
                        }
                    },
                        {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (!data) return '';
                            const statusKey = data;
                            const statusClass = {
                                'pending': 'warning',
                                'processing': 'info',
                                'completed': 'success',
                                'cancelled': 'danger',
                                'acceptedbysupplier': 'success',
                                'rejectedbysupplier': 'danger',
                                'assigntorep': 'info',
                                'rejectedbyrep': 'danger',
                                'acceptedbyrep': 'success',
                                'modifiedbysupplier': 'warning',
                                'modifiedbyrep': 'warning',
                                'outfordelivery': 'primary',
                                'delivered': 'success'
                            }[statusKey] || 'primary';
                            // Preload all translations into JS object with explicit Arabic locale
                            const statusTranslations = {
                                pending: '{{ trans("suborders.status.pending", [], 'ar') }}',
                                acceptedBySupplier: '{{ trans("suborders.status.acceptedBySupplier", [], 'ar') }}',
                                rejectedBySupplier: '{{ trans("suborders.status.rejectedBySupplier", [], 'ar') }}',
                                assignToRep: '{{ trans("suborders.status.assignToRep", [], 'ar') }}',
                                rejectedByRep: '{{ trans("suborders.status.rejectedByRep", [], 'ar') }}',
                                acceptedByRep: '{{ trans("suborders.status.acceptedByRep", [], 'ar') }}',
                                modifiedBySupplier: '{{ trans("suborders.status.modifiedBySupplier", [], 'ar') }}',
                                modifiedByRep: '{{ trans("suborders.status.modifiedByRep", [], 'ar') }}',
                                outForDelivery: '{{ trans("suborders.status.outForDelivery", [], 'ar') }}',
                                delivered: '{{ trans("suborders.status.delivered", [], 'ar') }}'
                            };

                            // Convert statusKey to camelCase to match translation keys
                            const camelCaseKey = statusKey.replace(/([a-z])([A-Z])/g, '$1$2')
                                .toLowerCase()
                                .replace(/([-_][a-z])/g, group =>
                                    group.toUpperCase()
                                        .replace('-', '')
                                        .replace('_', '')
                                );
                            const statusText = statusTranslations[camelCaseKey] || data;
                            const isArabic = '{{ app()->getLocale() }}' === 'ar';
                            const rtlStyle = isArabic ? 'text-align: right; direction: rtl;' : '';
                            return `<span class="badge badge-light-${statusClass} px-3 py-2" style="min-width: fit-content; ${rtlStyle}">${statusText}</span>`;
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[4, 'desc']],
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                responsive: true,
                dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
                      <'row'<'col-sm-12'tr>>
                      <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
                buttons: [
                    'print',
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5',
                    'pdfHtml5'
                ]
            });

            $('#generalSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#statusFilter').on('change', function() {
                table.draw();
            });

            $('#kt_reset').on('click', function(e) {
                e.preventDefault();
                $('#statusFilter').val('');
                table.search('').draw();
                $(this).addClass('kt-hidden');
            });

            $('#generalSearch, #statusFilter').on('input change', function() {
                $('#kt_reset').removeClass('kt-hidden');
            });

            // Date range picker
            $('#date-range-filter').daterangepicker({
                autoUpdateInput: false,
                showDropdowns: true,
                linkedCalendars: false,
                autoApply: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'MM/DD/YYYY',
                    separator: ' - ',
                    applyLabel: 'Apply',
                    cancelLabel: 'Clear',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                        'September', 'October', 'November', 'December'
                    ],
                    firstDay: 1
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                $('#orders-table').DataTable().draw();
            }).on('cancel.daterangepicker', function() {
                $(this).val('');
                $('#orders-table').DataTable().draw();
            });
        });
    </script>
@endpush
