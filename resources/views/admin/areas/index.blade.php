@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.areas.title')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.areas.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.areas.buttons.add')
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
                                        <input type="text" class="form-control" placeholder="@lang('messages.areas.search_placeholder')"
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="city-filter">
                                        <option value="">@lang('messages.areas.filters.all_cities')</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}">{{ $city->name_en }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">@lang('messages.areas.filters.all_statuses')</option>
                                        <option value="active">@lang('messages.areas.status.active')</option>
                                        <option value="inactive">@lang('messages.areas.status.inactive')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                            <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                <i class="la la-close"></i> @lang('messages.areas.buttons.reset')
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="areas-table">
                        <style>
                            #areas-table thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }

                            #areas-table thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>@lang('messages.areas.table_headers.id')</th>
                                <th>@lang('messages.areas.table_headers.name_en')</th>
                                <th>@lang('messages.areas.table_headers.name_ar')</th>
                                <th>@lang('messages.areas.table_headers.city')</th>
                                <th>@lang('messages.areas.table_headers.status')</th>
                                <th>@lang('messages.areas.table_headers.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded via AJAX -->
                        </tbody>
                    </table>
                </div>
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

            // Initialize DataTable with error handling
            try {
                var table = $('#areas-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.areas.datatable') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: function(d) {
                            return $.extend({}, d, {
                                search: $('#generalSearch').val(),
                                city_id: $('#city-filter').val(),
                                status: $('#statusFilter').val()
                            });
                        },
                        error: function(xhr, error, thrown) {
                            console.group('AJAX Error Details');
                            console.log('Status:', xhr.status);
                            console.log('Response:', xhr.responseText);
                            console.log('Error:', error);
                            console.log('Thrown:', thrown);
                            console.groupEnd();

                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                alert('Error: ' + xhr.responseJSON.message);
                            } else {
                                alert(
                                    'An error occurred while loading data. Please check the console for details.'
                                    );
                            }
                        }
                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'name_en',
                            name: 'name_en'
                        },
                        {
                            data: 'name_ar',
                            name: 'name_ar'
                        },
                        {
                            data: 'city.name_en',
                            name: 'city.name_en'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function(data) {
                                return data ?
                                    `<span class="kt-badge kt-badge--success">${data}</span>` : '';
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
                    order: [
                        [0, 'desc']
                    ],
                    lengthMenu: [10, 25, 50, 100],
                    pageLength: 10,
                    language: {
                        processing: '@lang('messages.areas.datatable.processing')',
                        emptyTable: '@lang('messages.areas.datatable.emptyTable')',
                        info: '@lang('messages.areas.datatable.info')',
                        infoEmpty: '@lang('messages.areas.datatable.infoEmpty')',
                        infoFiltered: '@lang('messages.areas.datatable.infoFiltered')',
                        lengthMenu: '@lang('messages.areas.datatable.lengthMenu')',
                        loadingRecords: '@lang('messages.areas.datatable.loadingRecords')',
                        search: '@lang('messages.areas.datatable.search')',
                        zeroRecords: '@lang('messages.areas.datatable.zeroRecords')'
                    },
                    initComplete: function() {
                        console.log('DataTable initialized successfully');
                    }
                });

                // Handle search input
                $('#generalSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });

                // Handle filter changes
                $('#city-filter, #statusFilter').on('change', function() {
                    table.draw();
                });

                // Reset filters
                $('#kt_reset').on('click', function(e) {
                    e.preventDefault();
                    table.search('').columns().search('').draw();
                    $(this).addClass('kt-hidden');
                });

                // Show reset button when filters are applied
                $('#generalSearch, #city-filter, #statusFilter').on('input change', function() {
                    $('#kt_reset').removeClass('kt-hidden');
                });

            } catch (error) {
                console.error('DataTable initialization error:', error);
            }
        });
    </script>
@endpush
