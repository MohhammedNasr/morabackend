@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">Manage Roles</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-plus"></i> Add Role
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
                    <table class="table table-striped- table-bordered table-hover table-checkable" id="roles-table">
                        <style>
                            #roles-table thead th {
                                position: sticky;
                                top: 0;
                                background: white;
                                z-index: 1;
                                box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
                            }
                            #roles-table thead th:first-child {
                                z-index: 2;
                            }
                        </style>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th>Actions</th>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.roles.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            search: $('#generalSearch').val()
                        });
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { 
                        data: 'created_at', 
                        name: 'created_at',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString() : '';
                        }
                    },
                    { 
                        data: 'updated_at', 
                        name: 'updated_at',
                        render: function(data) {
                            return data ? new Date(data).toLocaleDateString() : '';
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

            $('#kt_reset').on('click', function(e) {
                e.preventDefault();
                table.search('').columns().search('').draw();
                $(this).addClass('kt-hidden');
            });

            $('#generalSearch').on('input', function() {
                $('#kt_reset').removeClass('kt-hidden');
            });
        });
    </script>
@endpush
            </div>
        </div>
    </div>
@endsection
