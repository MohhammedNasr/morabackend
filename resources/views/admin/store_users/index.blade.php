@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Store Users - {{ $store->name }}</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.store_users.create', $store) }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> Add User
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
                                        <input type="text" class="form-control" placeholder="Search..."
                                            id="generalSearch">
                                        <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                            <span><i class="la la-search"></i></span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                    <select class="form-control" id="statusFilter">
                                        <option value="">All Statuses</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 order-1 order-xl-2 kt-align-right">
                            <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                                <i class="la la-close"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
                <!--end: Search Form -->

                <!--begin: Datatable -->
                <div class="table-responsive" style="max-height: 600px; overflow-y: auto; position: relative;">
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="store_users_table">
                        <style>
                            #store_users_table thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #store_users_table thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
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
            var table = $('#store_users_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.store_users.datatable', $store) }}",
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
                    { data: 'email', name: 'email' },
                    { data: 'phone', name: 'phone' },
                    {
                        data: 'role',
                        name: 'role',
                        render: function(data) {
                            return data || 'N/A';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            return data === 'active' ?
                                `<span class="kt-badge kt-badge--success">Active</span>` :
                                `<span class="kt-badge kt-badge--danger">Inactive</span>`;
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                    {
                        data: 'actions',
                        name: 'actions',
                        render: function(data, type, row) {
                            return `
                                <a href="${row.edit_url}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                                    <i class="la la-edit"></i>
                                </a>
                                <form action="${row.delete_url}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm('Are you sure?')">
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
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>',
                    emptyTable: 'No users found',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    lengthMenu: 'Show _MENU_ entries',
                    loadingRecords: 'Loading...',
                    search: 'Search:',
                    zeroRecords: 'No matching records found'
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
