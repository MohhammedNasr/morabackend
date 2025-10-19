@extends('layouts.metronic.supplier')

@section('content')
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container kt-container--fluid">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">@lang('messages.representatives.title')</h3>
            <div class="kt-subheader__group">
                <a href="{{ route('supplier.representatives.create') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                    <i class="la la-plus"></i> @lang('messages.representatives.buttons.add')
                </a>
            </div>
        </div>
    </div>
</div>

<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <!--begin: Search Form -->
            <div class="kt-form kt-form--label-right kt-margin-t-20 kt-margin-b-10">
                <div class="row align-items-center">
                    <div class="col-lg-9 col-xl-8">
                        <div class="row align-items-center">
                            <div class="col-md-4 kt-margin-b-20-tablet-and-mobile">
                                <div class="kt-input-icon kt-input-icon--left">
                                    <input type="text" class="form-control" placeholder="@lang('messages.search')" id="generalSearch">
                                    <span class="kt-input-icon__icon kt-input-icon__icon--left">
                                        <span><i class="la la-search"></i></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-xl-4 kt-align-right">
                        <a href="#" class="btn btn-default kt-hidden" id="kt_reset">
                            <i class="la la-close"></i> @lang('messages.reset')
                        </a>
                    </div>
                </div>
            </div>
            <!--end: Search Form -->

            <!--begin: Datatable -->
            <div class="table-responsive">
                <table class="table table-striped- table-bordered table-hover table-checkable" id="representative_table">
                    <thead>
                        <tr>
                            <th>@lang('messages.representatives.table_headers.name')</th>
                            <th>@lang('messages.representatives.table_headers.email')</th>
                            <th>@lang('messages.representatives.table_headers.phone')</th>
                            <th>@lang('messages.representatives.table_headers.status')</th>
                            <th>@lang('messages.actions')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endpush

@push('scripts')
    <script src="{{ asset('metronic_theme/assets/vendors/custom/datatables/datatables.bundle.js') }}" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
          var table = $('#representative_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('supplier.representatives.datatable') }}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function(xhr) {
                console.log('Making AJAX request to:', "{{ route('supplier.representatives.datatable') }}");
                console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
                console.log('DataTables version:', $.fn.dataTable.version);
                console.log('Request headers:', xhr.headers);
            },
            error: function(xhr, error, thrown) {
                console.error('AJAX Error:', xhr.responseText, error, thrown);
            },
            complete: function(xhr, status) {
                console.log('AJAX complete with status:', status);
            },
            data: function(d) {
                return $.extend({}, d, {
                    search: $('#generalSearch').val(),
                    status: $('#statusFilter').val()
                });
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    return `
                        <span class="kt-switch kt-switch--sm kt-switch--brand">
                            <label>
                                <input type="checkbox" ${row.is_active ? 'checked' : ''}
                                    onchange="toggleStatus(${row.id}, this)">
                                <span></span>
                            </label>
                        </span>
                    `;
                }
            },
            {
                data: 'actions',
                name: 'actions',
                render: function(data, type, row) {
                    return `
                        <a href="/supplier/representatives/${row.id}/edit" class="btn btn-sm btn-clean btn-icon" title="Edit">
                            <i class="la la-edit"></i>
                        </a>
                        <a href="javascript:;" class="btn btn-sm btn-clean btn-icon" title="Delete" onclick="confirmDelete(${row.id})">
                            <i class="la la-trash"></i>
                        </a>
                    `;
                },
                orderable: false,
                searchable: false
            }
        ],
        order: [[0, 'asc']],
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        dom: `<'row'<'col-sm-6 text-left'f><'col-sm-6 text-right'B>>
              <'row'<'col-sm-12'tr>>
              <'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
        buttons: [
            'print',
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        language: {
            "search": "@lang('messages.datatable.search')",
            "lengthMenu": "@lang('messages.datatable.show') _MENU_ @lang('messages.datatable.entries')",
            "info": "@lang('messages.datatable.info')",
            "infoEmpty": "@lang('messages.datatable.infoEmpty')",
            "infoFiltered": "@lang('messages.datatable.infoFiltered')",
            "zeroRecords": "@lang('messages.datatable.zeroRecords')",
            "paginate": {
                "first": "@lang('messages.datatable.first')",
                "last": "@lang('messages.datatable.last')",
                "next": "@lang('messages.datatable.next')",
                "previous": "@lang('messages.datatable.previous')"
            }
        }
    });

    // Search handler with debounce
    let searchTimeout;
    $('#generalSearch').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            table.search(this.value).draw();
            if(this.value.length > 0) {
                $('#kt_reset').removeClass('kt-hidden');
            } else {
                $('#kt_reset').addClass('kt-hidden');
            }
        }, 500);
    });

    $('#kt_reset').on('click', function(e) {
        e.preventDefault();
        $('#generalSearch').val('').trigger('keyup');
    });
});

function toggleStatus(id, checkbox) {
    const isActive = checkbox.checked;
    $.ajax({
        url: "{{ route('supplier.representatives.toggle-status', ['representative' => '__ID__']) }}".replace('__ID__', id),
        type: 'POST',
        data: { is_active: isActive },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        beforeSend: function() {
            checkbox.disabled = true;
        },
        success: function(response) {
            $('#representative_table').DataTable().ajax.reload(null, false);
            showToast('Status updated successfully', 'success');
        },
        error: function(xhr) {
            checkbox.checked = !isActive;
            showToast('Failed to update status', 'danger');
            console.error('Toggle error:', xhr.responseText);
        },
        complete: function() {
            checkbox.disabled = false;
        }
    });
}

function showToast(message, type) {
    const toast = `<div class="toast toast-${type}">${message}</div>`;
    $('body').append(toast);
    setTimeout(() => $('.toast').remove(), 3000);
}
</script>
<style>
.toast {
    position: fixed;
    bottom: 20px;
    right: 20px;
    padding: 15px;
    border-radius: 4px;
    color: white;
    z-index: 9999;
}
.toast-success { background-color: #1dc9b7; }
.toast-danger { background-color: #fd397a; }

/* Table font styling */
#representative_table {
    font-size: 0.9rem;
}
#representative_table th {
    font-weight: 600;
}
</style>
