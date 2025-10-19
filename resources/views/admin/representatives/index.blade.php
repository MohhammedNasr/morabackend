@extends('layouts.metronic.admin')

@section('content')
<div class="card">
    {{-- <div class="card-header">
        <h3 class="card-title">Representatives</h3>
        <div class="card-tools">
            <button class="btn btn-primary" data-toggle="modal" data-target="#createRepresentativeModal">
                <i class="fas fa-plus"></i> Add Representative
            </button>
        </div>
    </div> --}}
    <div class="card-header">
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
                        placeholder="@lang('messages.select_date_range')" style="min-width: 250px;" />
                </div>
                <a href="{{ route('admin.representatives.create') }}" class="btn btn-primary">
                    <i class="la la-plus"></i> @lang('messages.representatives.buttons.add')
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="representatives-table">
                        <thead>
                            <tr>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.id')</th>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.name')</th>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.email')</th>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.phone')</th>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.status')</th>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.created_at')</th>
                                <th style="{{ app()->getLocale() == 'ar' ? 'text-align:right' : 'text-align:left' }}">@lang('messages.representatives.table_headers.actions')</th>
                            </tr>
                        </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createRepresentativeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('messages.representatives.modals.create_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="createRepresentativeForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.name')</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.phone')</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.email')</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.password')</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.supplier')</label>
                        <select name="supplier_id" class="form-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.common.close')</button>
                    <button type="submit" class="btn btn-primary">@lang('messages.common.save')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editRepresentativeModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('messages.representatives.modals.edit_title')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editRepresentativeForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.name')</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.phone')</label>
                        <input type="text" name="phone" id="edit_phone" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.email')</label>
                        <input type="email" name="email" id="edit_email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.password_optional')</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>@lang('messages.representatives.modals.supplier')</label>
                        <select name="supplier_id" id="edit_supplier_id" class="form-control" required>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('messages.common.close')</button>
                    <button type="submit" class="btn btn-primary">@lang('messages.common.update')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(function() {
    var table = $('#representatives-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('admin.representatives.index') }}",
            data: function(d) {
                return $.extend({}, d, {
                    date_range: $('#date-range-filter').val()
                });
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'phone', name: 'phone' },
            { data: 'email', name: 'email' },
            { data: 'supplier.name', name: 'supplier.name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
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
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        $('#representatives-table').DataTable().draw();
    }).on('cancel.daterangepicker', function() {
        $(this).val('');
        $('#representatives-table').DataTable().draw();
    });

    // Create form submission
    $('#createRepresentativeForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.representatives.store') }}",
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#createRepresentativeModal').modal('hide');
                table.draw();
                toastr.success(response.message);
                $('#createRepresentativeForm')[0].reset();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    });

    // Edit form submission
    $('#editRepresentativeForm').submit(function(e) {
        e.preventDefault();
        var id = $('#edit_id').val();
        $.ajax({
            url: "/admin/representatives/" + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editRepresentativeModal').modal('hide');
                table.draw();
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    });
});

function editRepresentative(id) {
    $.get("/admin/representatives/" + id + "/edit", function(data) {
        $('#edit_id').val(data.representative.id);
        $('#edit_name').val(data.representative.name);
        $('#edit_phone').val(data.representative.phone);
        $('#edit_email').val(data.representative.email);
        $('#edit_supplier_id').val(data.representative.supplier_id);
        $('#editRepresentativeModal').modal('show');
    });
}

function deleteRepresentative(id) {
    if (confirm('Are you sure you want to delete this representative?')) {
        $.ajax({
            url: "/admin/representatives/" + id,
            method: 'DELETE',
            success: function(response) {
                $('#representatives-table').DataTable().draw();
                toastr.success(response.message);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message);
            }
        });
    }
}
</script>
@endpush
