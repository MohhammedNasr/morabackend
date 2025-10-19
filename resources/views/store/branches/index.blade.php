@extends('layouts.metronic.store')
@php
    app()->setLocale('ar');
@endphp
@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('messages.branches.title')</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('store.branches.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> @lang('messages.branches.create')
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
                                        <input type="text" class="form-control" placeholder="@lang('messages.branches.datatable.search')"
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">@lang('messages.branches.status.all')</option>
                                        <option value="1">@lang('messages.branches.status.active')</option>
                                        <option value="0">@lang('messages.branches.status.inactive')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                                <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                    <i class="la la-close"></i> @lang('messages.branches.datatable.reset')
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="branches_datatable">
                        <style>
                            #branches_datatable thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #branches_datatable thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>@lang('messages.branches.table_headers.id')</th>
                                <th>@lang('messages.branches.table_headers.name')</th>
                                <th>@lang('messages.branches.table_headers.main_name')</th>
                                <th>@lang('messages.branches.table_headers.street')</th>
                                <th>@lang('messages.branches.table_headers.building')</th>
                                <th>@lang('messages.branches.table_headers.floor')</th>
                                <th>@lang('messages.branches.table_headers.phone')</th>
                                <th>@lang('messages.branches.table_headers.city')</th>
                                <th>@lang('messages.branches.table_headers.area')</th>
                                <th>@lang('messages.branches.table_headers.coordinates')</th>
                                <th>@lang('messages.branches.table_headers.main_branch')</th>
                                <th>@lang('messages.branches.table_headers.status')</th>
                                <th>@lang('messages.branches.table_headers.actions')</th>
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
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
        type="text/javascript"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            var table = $('#branches_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('store.branches.datatable') }}",
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
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'main_name', name: 'main_name' },
                    { data: 'street_name', name: 'street_name' },
                    { data: 'building_number', name: 'building_number' },
                    { data: 'floor_number', name: 'floor_number' },
                    { data: 'phone', name: 'phone' },
                    {
                        data: 'city',
                        name: 'city.name_en',
                        render: function(data) {
                            return data ? data.name_en : '';
                        }
                    },
                    {
                        data: 'area',
                        name: 'area.name_en',
                        render: function(data) {
                            return data ? data.name_en : '';
                        }
                    },
                    {
                        data: 'coordinates',
                        name: 'coordinates',
                        render: function(data, type, row) {
                            return row.latitude + ', ' + row.longitude;
                        }
                    },
                    {
                        data: 'is_main',
                        name: 'is_main',
                        render: function(data) {
                            return data ?
                                '<span class="kt-badge kt-badge--success">@lang('branches.main_branch.yes')</span>' :
                                '<span class="kt-badge kt-badge--danger">@lang('branches.main_branch.no')</span>';
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data) {
                            return data ?
                                '<span class="kt-badge kt-badge--success">@lang('messages.branches.status.active')</span>' :
                                '<span class="kt-badge kt-badge--danger">@lang('messages.branches.status.inactive')</span>';
                        }
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data, type, row) {
                            return `
                                <a href="${row.edit_url}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                                    <i class="la la-edit"></i>
                                </a>
                                <form action="${row.delete_url}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm('@lang('messages.branches.delete_confirm')')">
                                        <i class="la la-trash"></i>
                                    </button>
                                </form>
                            `;
                        },
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[0, 'desc']],
                lengthMenu: [10, 25, 50, 100],
                pageLength: 10,
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> @lang('messages.branches.datatable.processing')',
                    emptyTable: '@lang('messages.branches.datatable.empty')',
                    info: '@lang('messages.branches.datatable.info')',
                    infoEmpty: '@lang('messages.branches.datatable.info_empty')',
                    infoFiltered: '@lang('messages.branches.datatable.info_filtered')',
                    lengthMenu: '@lang('messages.branches.datatable.length_menu')',
                    loadingRecords: '@lang('messages.branches.datatable.loading')',
                    search: '@lang('messages.branches.datatable.search')',
                    zeroRecords: '@lang('messages.branches.datatable.zero_records')'
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
        });
    </script>
@endpush
