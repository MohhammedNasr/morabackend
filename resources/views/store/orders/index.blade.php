@extends('layouts.metronic.admin')

@section('content')
@php
    $isRTL = app()->getLocale() === 'ar';
@endphp

<style>
    .rtl-support {
        direction: {{ $isRTL ? 'rtl' : 'ltr' }};
        text-align: {{ $isRTL ? 'right' : 'left' }};
    }
    .rtl-support table {
        direction: {{ $isRTL ? 'rtl' : 'ltr' }};
    }
    .rtl-support .kt-subheader__title {
        text-align: {{ $isRTL ? 'right' : 'left' }};
    }
    .rtl-support .kt-input-icon__icon {
        {{ $isRTL ? 'right' : 'left' }}: 0;
    }
    .rtl-support .kt-align-right {
        text-align: {{ $isRTL ? 'left' : 'right' }};
    }
</style>
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item rtl-support" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.store_orders.title')</h3>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid rtl-support">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <!--begin: Search Form -->
                <div class="kt-form kt-form--label-right kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder="@lang('messages.store_orders.table_headers.search')" id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">@lang('messages.store_orders.status.all')</option>
                                        <option value="pending">@lang('messages.store_orders.status.pending')</option>
                                        <option value="approved">@lang('messages.store_orders.status.approved')</option>
                                        <option value="rejected">@lang('messages.store_orders.status.rejected')</option>
                                        <option value="delivered">@lang('messages.store_orders.status.delivered')</option>
                                        <option value="cancelled">@lang('messages.store_orders.status.cancelled')</option>
                                    </select>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="branchFilter">
                                            <option value="">@lang('messages.store_orders.branches.all')</option>
                                        @foreach(auth()->user()->store->branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                                <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                    <i class="la la-close"></i> @lang('store_orders.buttons.reset')
                                </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="orders_datatable">
                        <style>
                            #orders_datatable thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #orders_datatable thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>@lang('messages.store_orders.table_headers.id')</th>
                                <th>@lang('messages.store_orders.table_headers.branch')</th>
                                <th>@lang('messages.store_orders.table_headers.supplier')</th>
                                <th>@lang('messages.store_orders.table_headers.status')</th>
                                <th>@lang('messages.store_orders.table_headers.total')</th>
                                <th>@lang('messages.store_orders.table_headers.due_date')</th>
                                <th>@lang('messages.store_orders.table_headers.actions')</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            var isRTL = $('html').attr('dir') === 'rtl';
            var table = $('#orders_datatable').DataTable({
                dom: "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('store.orders.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            search: $('#generalSearch').val(),
                            status: $('#statusFilter').val(),
                            branch: $('#branchFilter').val()
                        });
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'branch_name', name: 'branch_name' },
                    {
                        data: 'suppliers',
                        name: 'suppliers',
                        render: function(data) {
                            return data ? data.join(', ') : '-';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return `<span class="kt-badge kt-badge--${row.status_color}">${data}</span>`;
                        }
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: function(data) {
                            return data ? `${parseFloat(data).toFixed(2)} SAR` : '0.00 SAR';
                        }
                    },
                    {
                        data: 'payment_due_date',
                        name: 'payment_due_date',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString() : '-';
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data) {
                            return data || '';
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
                    emptyTable: 'No orders found',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    lengthMenu: 'Show _MENU_ entries',
                    loadingRecords: 'Loading...',
                    search: 'Search:',
                    zeroRecords: 'No matching records found'
                },
                createdRow: function(row, data, dataIndex) {
                    if (isRTL) {
                        $(row).addClass('text-right');
                    }
                }
            });

            // Handle search input
            $('#generalSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Handle filter changes
            $('#statusFilter, #branchFilter').on('change', function() {
                table.draw();
            });

            // Reset filters
            $('#kt_reset').on('click', function(e) {
                e.preventDefault();
                table.search('').columns().search('').draw();
                $('#statusFilter, #branchFilter').val('');
                $(this).addClass('kt-hidden');
            });

            // Show reset button when filters are applied
            $('#generalSearch, #statusFilter, #branchFilter').on('input change', function() {
                $('#kt_reset').removeClass('kt-hidden');
            });
        });
    </script>
@endpush
