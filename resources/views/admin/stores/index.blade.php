@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.Stores')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.stores.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.stores.buttons.add')
                    </a>
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
                <div class="kt-form kt-form--label-right kt-margin-b-10">
                    <div class="row align-items-center">
                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <div class="kt-input-icon kt-input-icon--left">
                                        <input type="text" class="form-control" placeholder=".."
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">@lang('messages.stores.filters.all_statuses')</option>
                                        <option value="active">@lang('messages.stores.status.active')</option>
                                        <option value="inactive">@lang('messages.stores.status.inactive')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                            <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                <i class="la la-close"></i> @lang('Reset')
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="stores_table">
                        <style>
                            #stores_table thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #stores_table thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>@lang('messages.stores.table_headers.id')</th>
                                <th>@lang('messages.stores.table_headers.name')</th>
                                <th>@lang('messages.stores.table_headers.owner_name')</th>
                                <th>@lang('messages.stores.table_headers.owner_email')</th>
                                <th>@lang('messages.stores.table_headers.phone')</th>
                                <th>@lang('messages.stores.table_headers.commercial_record')</th>
                                <th>@lang('messages.stores.table_headers.credit_limit')</th>
                                <th>@lang('messages.stores.table_headers.Active')</th>
                                <th>@lang('messages.stores.table_headers.Verified')</th>
                                <th>@lang('messages.stores.table_headers.auto_verify')</th>
                                <th>@lang('messages.stores.table_headers.orders')</th>
                                <th>@lang('messages.stores.table_headers.created_at')</th>
                                <th>@lang('messages.stores.table_headers.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
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
            var table = $('#stores_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.stores.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            search: $('#generalSearch').val(),
                            status: $('#statusFilter').val()
                        });
                    },
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error:', xhr, error, thrown);
                        alert('An error occurred while loading data. Please check the console for details.');
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    {
                        data: 'owner_name',
                        name: 'owner_name',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'owner_email',
                        name: 'owner_email',
                        render: function(data, type, row) {
                            return data || 'N/A';
                        }
                    },
                    { data: 'phone', name: 'phone' },
                    { data: 'commercial_record', name: 'commercial_record' },
                    { data: 'credit_limit', name: 'credit_limit' },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `
                                    <label class="kt-switch kt-switch--sm kt-switch--success">
                                        <input type="checkbox" ${data == 1 ? 'checked' : ''}
                                            data-id="${row.id}" data-field="is_active" class="status-toggle">
                                        <span></span>
                                    </label>
                                `;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'is_verified',
                        name: 'is_verified',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `
                                    <label class="kt-switch kt-switch--sm kt-switch--brand">
                                        <input type="checkbox" ${data == 1 ? 'checked' : ''}
                                            data-id="${row.id}" data-field="is_verified" class="status-toggle">
                                        <span></span>
                                    </label>
                                `;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'auto_verify_order',
                        name: 'auto_verify_order',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `
                                    <label class="kt-switch kt-switch--sm kt-switch--info">
                                        <input type="checkbox" ${data == 1 ? 'checked' : ''}
                                            data-id="${row.id}" data-field="auto_verify_order" class="status-toggle">
                                        <span></span>
                                    </label>
                                `;
                            }
                            return data;
                        }
                    },
                    { data: 'orders_count', name: 'orders_count' },
                    { data: 'created_at', name: 'created_at' },
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
                language: @json(__('messages.stores.datatable'))
            });

            // Handle search input
            $('#generalSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Handle filter changes
            $('#statusFilter').on('change', function() {
                table.draw();
            });

            // Reset filters
            $('#kt_reset').on('click', function(e) {
                e.preventDefault();
                table.search('').columns().search('').draw();
                $(this).addClass('kt-hidden');
            });

            // Show reset button when filters are applied
            $('#generalSearch, #statusFilter').on('input change', function() {
                $('#kt_reset').removeClass('kt-hidden');
            });

            // Handle status toggle
            $('#stores_table').on('change', '.status-toggle', function() {
                const storeId = $(this).data('id');
                const field = $(this).data('field');
                const value = $(this).is(':checked');

                $.ajax({
                    url: `/admin/stores/${storeId}/status`,
                    method: 'PUT',
                    data: {
                        field: field,
                        value: value ? '1' : '0',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        $(this).prop('checked', !value);
                        toastr.error(xhr.responseJSON.message || 'Error updating status');
                    }
                });
            });
        });
    </script>
@endpush
