@extends('layouts.metronic.base')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3
                class="card-title" @if (app()->getLocale() === 'ar') style='text-align:right;' @else style='text-align:left' @endif">
                @lang('messages.users.title')</h3>
            <div class="card-toolbar">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center position-relative">
                        <i class="ki-duotone ki-calendar fs-3 position-absolute ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                        <input type="text" class="form-control form-control-solid ps-12" id="date-range-filter"
                            placeholder="@lang('common.select_date_range')" style="min-width: 250px;" />
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group">
                            <label
                                class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">@lang('messages.users.filters.status')</label>
                            <select class="form-control kt-select2" id="status-filter" name="status">
                                <option value="">@lang('common.all')</option>
                                <option value="1">@lang('common.active')</option>
                                <option value="0">@lang('common.inactive')</option>
                            </select>
                        </div>
                    </div>
                    {{--
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label>Role</label>
                            <select class="form-control kt-select2" id="role-filter" name="role_id">
                                <option value="">All</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}

                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i> @lang('messages.users.buttons.add')
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-striped table-row-bordered gy-5 gs-7" id="users-table">
                <thead>
                    <tr
                        class="fw-bold fs-6 text-gray-800 @if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                        <th>@lang('messages.users.table_headers.id')</th>
                        <th>@lang('messages.users.table_headers.name')</th>
                        <th>@lang('messages.users.table_headers.email')</th>
                        <th>@lang('messages.users.table_headers.phone')</th>
                        <th>@lang('messages.users.table_headers.roles')</th>
                        <th>@lang('messages.users.table_headers.stores')</th>
                        <th>@lang('messages.users.table_headers.status')</th>
                        <th>@lang('messages.users.table_headers.created_at')</th>
                        <th>@lang('messages.users.table_headers.actions')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}"
        type="text/javascript"></script>
    <script>
        "use strict";
        $(document).ready(function() {
            console.log('Initializing DataTable...');

            var table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.users.datatable') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            is_active: $('#status-filter').val(),
                            role_id: $('#role-filter').val(),
                            date_range: $('#date-range-filter').val()
                        });
                    },
                    error: function(xhr, error, thrown) {
                        console.error('AJAX Error:', xhr, error, thrown);
                        alert(
                            'An error occurred while loading data. Please check the console for details.'
                        );
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },

                    {
                        data: 'stores',
                        name: 'stores',
                        render: function(data) {
                            return data || '';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                const isActive = data === 'active';
                                return `
                                    <label class="kt-switch kt-switch--sm kt-switch--success">
                                        <input type="checkbox" ${row.is_active == 1 ? 'checked' : ''}
                                            data-id="${row.id}" class="status-toggle">
                                        <span></span>
                                    </label>
                                `;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return data ? moment(data).format('YYYY-MM-DD HH:mm') : '';
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
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i> @lang('messages.users.datatable.processing')',
                    emptyTable: '@lang('messages.users.datatable.emptyTable')',
                    info: '@lang('messages.users.datatable.info')',
                    infoEmpty: '@lang('messages.users.datatable.infoEmpty')',
                    infoFiltered: '@lang('messages.users.datatable.infoFiltered')',
                    lengthMenu: '@lang('messages.users.datatable.lengthMenu')',
                    loadingRecords: '@lang('messages.users.datatable.loadingRecords')',
                    search: '@lang('messages.users.datatable.search')',
                    zeroRecords: '@lang('messages.users.datatable.zeroRecords')'
                }
            });

            // Handle filter changes
            $('#status-filter, #role-filter').on('change', function() {
                table.draw();
            });

            // Date range picker
            $('#date-range-filter').daterangepicker({
                autoUpdateInput: false,
                showDropdowns: true,
                linkedCalendars: false,
                autoApply: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                locale: {
                    format: 'MM/DD/YYYY',
                    separator: ' - ',
                    applyLabel: 'Apply',
                    cancelLabel: 'Clear',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August',
                        'September', 'October', 'November', 'December'
                    ],
                    firstDay: 1
                }
            }).on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                table.draw();
            }).on('cancel.daterangepicker', function() {
                $(this).val('');
                table.draw();
            });

            // Handle status toggle
            $('#users-table').on('change', '.status-toggle', function() {
                const userId = $(this).data('id');
                const isActive = $(this).is(':checked');

                $.ajax({
                    url: `/admin/users/${userId}/status`,
                    method: 'PUT',
                    data: {
                        is_active: isActive ? '1' : '0',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr) {
                        $(this).prop('checked', !isActive);
                        toastr.error(xhr.responseJSON.message || 'Error updating status');
                    }
                });
            });
        });
    </script>
@endpush
