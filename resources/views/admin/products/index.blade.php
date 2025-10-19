@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.products.title')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.products.buttons.add')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('messages.products.index')</h3>

            <div class="card-toolbar">
                <div class="row">
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> @lang('messages.products.buttons.add')
                    </a>
                    <a href="{{ route('admin.products.import') }}" class="btn btn-primary d-flex align-items-center px-4 me-4" style="gap: 8px;">
                        <i class="fas fa-file-import"></i>
                        <span>@lang('messages.products.buttons.import')</span>
                    </a>

                    {{-- <div class="clearfix"></div>
                    <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data"
                        class="d-inline">
                        @csrf
                    <a href="{{ route('admin.products.import') }}" class="btn btn-success">
                        <i class="fas fa-file-import"></i> Import Products
                    </a>
                    </form> --}}
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
                                            <input type="text" class="form-control" placeholder="@lang('messages.products.filters.search')"
                                                id="generalSearch">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                        <select class="form-control" id="statusFilter">
                                            <option value="">@lang('messages.products.filters.all_statuses')</option>
                                            <option value="active">@lang('messages.products.status.active')</option>
                                            <option value="deleted">@lang('messages.products.status.deleted')</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                        <select class="form-control" id="categoryFilter">
                                            <option value="">@lang('messages.products.filters.all_categories')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                                            @endforeach
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
                                <th>@lang('messages.products.table_headers.id')</th>
                                <th>@lang('messages.products.table_headers.image')</th>
                                <th>@lang('messages.products.table_headers.name_en')</th>
                                <th>@lang('messages.products.table_headers.name_ar')</th>
                                <th>@lang('messages.products.table_headers.sku')</th>
                                <th>@lang('messages.products.table_headers.price')</th>
                                <th>@lang('messages.products.table_headers.category')</th>
                                <th>@lang('messages.products.table_headers.status')</th>
                                <th>@lang('messages.products.table_headers.actions')</th>
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
            <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}"
                rel="stylesheet" type="text/css" />
        @endpush

        @push('scripts')
            @if (!isset($jqueryLoaded))
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                @php $jqueryLoaded = true @endphp
            @endif
            <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
                type="text/javascript"></script>
            <script src="{{ asset('metronic_theme/assets/vendors/custom/owl-carousel/owl.carousel.js') }}"></script>
            <script src="{{ asset('metronic_theme/assets/vendors/custom/bootstrap-datepicker/bootstrap-datepicker.js') }}">
            </script>
            <script src="{{ asset('metronic_theme/assets/vendors/custom/select2/select2.js') }}"></script>
            <script>
                "use strict";
                $(document).ready(function() {
                    console.log('Initializing DataTable...');

                    // Initialize DataTable with error handling
                    try {
                        var table = $('#kt_table_2').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('admin.products.datatable') }}",
                                type: "POST",
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    'Accept': 'application/json'
                                },
                                data: function(d) {
                                    return $.extend({}, d, {
                                        search: $('#generalSearch').val(),
                                        status: $('#statusFilter').val(),
                                        category: $('#categoryFilter').val()
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
                                    data: 'image',
                                    name: 'image',
                                    render: function(data) {
                                        if (!data) {
                                            return '<div class="text-muted">No image</div>';
                                        } else {

                                            return data;
                                            // `<img src="${data}" width="50" class="img-thumbnail">`;

                                        }
                                        // If data already contains a full URL, use it as-is
                                        //  if (data.startsWith('http://') || data.startsWith('https://')) {
                                        //     return `<img src="${data}" width="50" class="img-thumbnail">`;
                                        // }
                                        // Otherwise, ensure proper URL formatting
                                        //  const imageUrl = data.startsWith('/') ? data : '/' + data;
                                        //return `<img src="${imageUrl}" width="50" class="img-thumbnail">`;
                                    },
                                    orderable: false,
                                    searchable: false
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
                                    data: 'sku',
                                    name: 'sku'
                                },
                                {
                                    data: 'price',
                                    name: 'price',
                                    render: function(data) {
                                        return data ? `$${parseFloat(data).toFixed(2)}` : '';
                                    }
                                },
                                {
                                    data: 'category.name_en',
                                    name: 'category.name_en',
                                    render: function(data) {
                                        return data || 'N/A';
                                    }
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
                            language: @json(__('messages.products.datatable')),
                            initComplete: function() {
                                console.log('DataTable initialized successfully');
                            }
                        });

                        // Handle search input
                        $('#generalSearch').on('keyup', function() {
                            table.search(this.value).draw();
                        });

                        // Handle filter changes
                        $('#statusFilter, #categoryFilter').on('change', function() {
                            table.draw();
                        });

                        // Reset filters
                        $('#kt_reset').on('click', function(e) {
                            e.preventDefault();
                            $('#categoryFilter').val('');
                            table.search('').columns().search('').draw();
                            $(this).addClass('kt-hidden');
                        });

                        // Show reset button when filters are applied
                        $('#generalSearch, #statusFilter, #categoryFilter').on('input change', function() {
                            $('#kt_reset').removeClass('kt-hidden');
                        });

                    } catch (error) {
                        console.error('DataTable initialization error:', error);
                    }
                });
            </script>
        @endpush
