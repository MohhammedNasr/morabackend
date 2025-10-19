@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.categories.title')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.categories.create')
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
                                        <input type="text" class="form-control" placeholder="@lang('messages.categories.filters.search')"
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">@lang('messages.categories.filters.all_statuses')</option>
                                        <option value="active">@lang('messages.categories.filters.active')</option>
                                        <option value="inactive">@lang('messages.categories.filters.inactive')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                            <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                <i class="la la-close"></i> @lang('messages.categories.filters.reset')
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_2">
                        <style>
                            #kt_table_2 thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #kt_table_2 thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                    <thead>
                        <tr>
                            <th>@lang('messages.categories.table_headers.id')</th>
                            <th>@lang('messages.categories.table_headers.image')</th>
                            <th>@lang('messages.categories.table_headers.name_en')</th>
                            <th>@lang('messages.categories.table_headers.name_ar')</th>
                            <th>@lang('messages.categories.table_headers.status')</th>
                            <th>@lang('messages.categories.table_headers.products')</th>
                            <th>@lang('messages.categories.table_headers.actions')</th>
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
    @if (!isset($jqueryLoaded))
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        @php $jqueryLoaded = true @endphp
    @endif
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
        type="text/javascript"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            console.log('Initializing DataTable...');

            var table = $('#kt_table_2').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.categories.datatable') }}",
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
                    {
                        data: 'image',
                        name: 'image',
                        render: function(data) {
                            return data ? `<img src="${data}" width="50" class="img-thumbnail">` :
                                   '<div class="text-muted">No image</div>';
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'name_en', name: 'name_en' },
                    { data: 'name_ar', name: 'name_ar' },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `
                                    <label class="kt-switch kt-switch--sm kt-switch--brand">
                                        <input type="checkbox" class="category-status-toggle"
                                            data-category-id="${row.id}"
                                            ${data === 'active' ? 'checked' : ''}>
                                        <span></span>
                                    </label>
                                `;
                            }
                            return data;
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
                    emptyTable: '@lang("messages.datatable.empty")',
                    info: '@lang("messages.datatable.info")',
                    infoEmpty: '@lang("messages.datatable.info_empty")',
                    infoFiltered: '@lang("messages.datatable.info_filtered")',
                    lengthMenu: '@lang("messages.datatable.length_menu")',
                    loadingRecords: '@lang("messages.datatable.loading")',
                    search: '@lang("messages.datatable.search")',
                    zeroRecords: '@lang("messages.datatable.zero_records")'
                }
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

            // Handle category status toggle change
            $(document).on('change', '.category-status-toggle', function() {
                const categoryId = $(this).data('category-id');
                const isActive = $(this).is(':checked');

                $.ajax({
                    url: "{{ route('admin.categories.toggle-status', ['category' => '__ID__']) }}".replace('__ID__', categoryId),
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        status: isActive ? 'active' : 'inactive'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            table.draw(false);
                        } else {
                            toastr.error(response.message);
                            // Revert the toggle if failed
                            $(this).prop('checked', !isActive);
                        }
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message || 'An error occurred');
                        // Revert the toggle on error
                        $(this).prop('checked', !isActive);
                    }
                });
            });
        });
    </script>
@endpush
