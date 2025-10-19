@extends('layouts.metronic.supplier')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.supplier_dashboard.products.page_title')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('supplier.products.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.supplier_dashboard.products.add_product')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('messages.supplier_dashboard.products.page_title')</h3>
            <div class="card-toolbar">
                <a href="{{ route('supplier.products.create') }}" class="btn btn-primary mr-2">
                    <i class="fas fa-plus"></i> @lang('messages.supplier_dashboard.products.add_product')
                </a>
                <a href="{{ route('supplier.products.import') }}" class="btn btn-success">
                    <i class="fas fa-file-import"></i> @lang('messages.products.import')
                </a>
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
                                    <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                                        <div class="kt-input-icon kt-input-icon--left">
                                            <input type="text" class="form-control" placeholder="@lang('messages.supplier_dashboard.products.search_placeholder')"
                                                id="generalSearch">
                                            <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                                <span><i class="la la-search"></i></span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 kt-margin-b-20-tablet-and-mobile">
                                        <select class="form-control" id="categoryFilter">
                                            <option value="">@lang('messages.all_categories')</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">
                                                    {{ app()->getLocale() === 'ar' ? $category->name_ar : $category->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                                <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                    <i class="la la-close"></i> @lang('messages.supplier_dashboard.products.reset')
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--end: Search Form -->

                    <!--begin: Datatable -->
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                        <table class="table table-striped- table-bordered table-hover table-checkable" id="products_table">
                            <style>
                                #products_table thead th {
                                    position: sticky;
                                    top: 0;
                                    background: white;
                                    z-index: 1;
                                    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                                }

                                #products_table thead th:first-child {
                                    z-index: 2;
                                }
                            </style>
                            <thead>
                                <tr>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.id')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.image')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.name_en')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.name_ar')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.sku')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.price')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.category')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.status')</th>
                                    <th>@lang('messages.supplier_dashboard.products.table_headers.actions')</th>
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
    </div>
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
        type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            var table = $('#products_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('supplier.products.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            search: $('#generalSearch').val(),
                            category: $('#categoryFilter').val()
                        });
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
                            return data || '<div class="text-muted">No image</div>';
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
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10
            });

            $('#generalSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('#categoryFilter').on('change', function() {
                table.draw();
            });

            $('#kt_reset').on('click', function(e) {
                e.preventDefault();
                $('#categoryFilter').val('');
                table.search('').draw();
                $(this).addClass('kt-hidden');
            });

            $('#generalSearch, #categoryFilter').on('input change', function() {
                $('#kt_reset').removeClass('kt-hidden');
            });
        });
    </script>
@endpush
