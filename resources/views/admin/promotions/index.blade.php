@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.promotions.title')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.promotions.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.promotions.create')
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
                                        <input type="text" class="form-control" placeholder="@lang('messages.promotions.filters.search')"
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">@lang('messages.promotions.filters.all_statuses')</option>
                                        <option value="active">@lang('messages.promotions.filters.active')</option>
                                        <option value="inactive">@lang('messages.promotions.filters.inactive')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                                <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                <i class="la la-close"></i> @lang('messages.promotions.filters.reset')
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_promotions_table">
                        <style>
                            #kt_promotions_table thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #kt_promotions_table thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                    <thead>
                        <tr>
                            <th>@lang('messages.promotions.table_headers.id')</th>
                            <th>@lang('messages.promotions.table_headers.code')</th>
                            <th>@lang('messages.promotions.table_headers.description')</th>
                            <th>@lang('messages.promotions.table_headers.discount_type')</th>
                            <th>@lang('messages.promotions.table_headers.discount')</th>
                            <th>@lang('messages.promotions.table_headers.min_order')</th>
                            <th>@lang('messages.promotions.table_headers.max_discount')</th>
                            <th>@lang('messages.promotions.table_headers.usage_limit')</th>
                            <th>@lang('messages.promotions.table_headers.used')</th>
                            <th>@lang('messages.promotions.table_headers.start_date')</th>
                            <th>@lang('messages.promotions.table_headers.end_date')</th>
                            <th>@lang('messages.promotions.table_headers.status')</th>
                            <th>@lang('messages.promotions.table_headers.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
                <!--end: Datatable -->
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
        type="text/javascript"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            var table = $('#kt_promotions_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.promotions.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            search: $('#generalSearch').val(),
                            status: $('#statusFilter').val()
                        });
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code', name: 'code' },
                    { data: 'description', name: 'description' },
                    { data: 'discount_type', name: 'discount_type' },
                    {
                        data: 'discount',
                        name: 'discount',
                        render: function(data) {
                            return data || '';
                        }
                    },
                    {
                        data: 'minimum_order_amount',
                        name: 'minimum_order_amount',
                        render: function(data) {
                            return data ? `$${data}` : '';
                        }
                    },
                    {
                        data: 'maximum_discount_amount',
                        name: 'maximum_discount_amount',
                        render: function(data) {
                            return data ? `$${data}` : '';
                        }
                    },
                    {
                        data: 'usage_limit',
                        name: 'usage_limit',
                        render: function(data) {
                            return data || 'Unlimited';
                        }
                    },
                    {
                        data: 'used_count',
                        name: 'used_count',
                        render: function(data) {
                            return data || 0;
                        }
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString() : '';
                        }
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString() : '';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data ?

                                `<span class="kt-badge kt-badge--${data === 'active' ? 'success' : 'danger'}">${data}</span>` : '';
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10
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
                table.search('').columns().search('').draw();
                $(this).addClass('kt-hidden');
            });

            $('#generalSearch, #statusFilter').on('input change', function() {
                $('#kt_reset').removeClass('kt-hidden');
            });

            // Handle status toggle
            $(document).on('click', '.toggle-status-btn', function() {
                const button = $(this);
                const url = button.data('url');
                const currentStatus = button.data('status');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        button.prop('disabled', true);
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Update button state
                            const newStatus = response.new_status;
                            button.find('i')
                                .toggleClass('la-toggle-on la-toggle-off')
                                .attr('title', newStatus === 'active' ? 'Deactivate' : 'Activate');
                            button.data('status', newStatus);

                            // Update status badge in table
                            const row = button.closest('tr');
                            row.find('.kt-badge')
                                .toggleClass('kt-badge--success kt-badge--danger')
                                .text(newStatus);

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
