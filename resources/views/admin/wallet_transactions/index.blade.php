@extends('layouts.metronic.admin')

@section('content')
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        @if (isset($walletId))
                            {{ __('messages.wallet.transactions.for_wallet', ['id' => $walletId]) }}
                        @else
                            {{ __('messages.wallet.transactions.all') }}
                        @endif
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <div class="kt-portlet__head-wrapper">
                        <a href="{{ route('admin.wallet_transactions.create') }}"
                            class="btn btn-brand btn-elevate btn-icon-sm">
                            <i class="la la-plus"></i>
                            {{ __('messages.wallet.transactions.add_new') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="kt-portlet__body">
                <table class="table table-striped- table-bordered table-hover table-checkable"
                    id="wallet_transactions_table">
                    <thead>
                        <tr>
                            <th>{{ __('messages.wallet.table.id') }}</th>
                            <th>{{ __('messages.wallet.table.transaction_id') }}</th>
                            <th>{{ __('messages.wallet.table.wallet') }}</th>
                            <th>{{ __('messages.wallet.table.amount') }}</th>
                            <th>{{ __('messages.wallet.table.type') }}</th>
                            <th>{{ __('messages.wallet.table.status') }}</th>
                            <th>{{ __('messages.wallet.table.created_at') }}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">{{ __('messages.wallet.transactions.change_status') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="statusForm">
                        @csrf
                        <input type="hidden" name="transaction_id" id="transaction_id">
                        <div class="form-group">
                            <label for="status">{{ __('messages.wallet.transactions.status_label') }}</label>
                            <select class="form-control" id="status" name="status">
                                <option value="pending">{{ __('messages.wallet.transaction_status.pending') }}</option>
                                <option value="completed">{{ __('messages.wallet.transaction_status.completed') }}</option>
                                <option value="failed">{{ __('messages.wallet.transaction_status.failed') }}</option>
                                <option value="refunded">{{ __('messages.wallet.transaction_status.refunded') }}</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="saveStatus">{{ __('messages.common.save_changes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#wallet_transactions_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.wallet_transactions.datatable') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function(d) {
                        return $.extend({}, d, {
                            wallet_id: '{{ $walletId ?? '' }}',
                            transaction_id: $('input[name="transaction_id"]').val()
                        });
                    }
                },
                initComplete: function() {
                    this.api().columns([1]).every(function() {
                        var column = this;
                        var input = $('<input type="text" name="transaction_id" placeholder="Search Transaction ID" class="form-control form-control-sm"/>')
                            .appendTo($(column.header()).empty())
                            .on('keyup change', function() {
                                if (column.search() !== this.value) {
                                    column.search(this.value).draw();
                                }
                            });
                    });
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'transaction_id', 
                        name: 'transaction_id'
                    },
                    {
                        data: 'wallet.store.name',
                        name: 'wallet.store.name'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex align-items-center">
                                    <span class="kt-badge kt-badge--inline kt-badge--pill 
                                        ${data === 'completed' ? 'kt-badge--success' : 
                                          data === 'pending' ? 'kt-badge--warning' : 
                                          data === 'failed' ? 'kt-badge--danger' : 
                                          'kt-badge--primary'}">
                                        ${data === 'completed' ? '{{ __('messages.wallet.transaction_status.completed') }}' : 
                                          data === 'pending' ? '{{ __('messages.wallet.transaction_status.pending') }}' : 
                                          data === 'failed' ? '{{ __('messages.wallet.transaction_status.failed') }}' : 
                                          '{{ __('messages.wallet.transaction_status.refunded') }}'}
                                    </span>
                                    <button class="btn btn-sm btn-icon btn-warning ml-2 change-status" 
                                        data-id="${row.id}" 
                                        data-status="${row.status}"
                                        title="Change Status">
                                        <i class="la la-edit"></i>
                                    </button>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    }
                ],
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                autoWidth: false,
                language: {
                    emptyTable: "{{ __('messages.datatable.empty') }}",
                    info: "{{ __('messages.datatable.info') }}",
                    infoEmpty: "{{ __('messages.datatable.info_empty') }}",
                    infoFiltered: "{{ __('messages.datatable.info_filtered') }}",
                    lengthMenu: "{{ __('messages.datatable.length_menu') }}",
                    loadingRecords: "{{ __('messages.datatable.loading') }}",
                    processing: "{{ __('messages.datatable.processing') }}",
                    search: "{{ __('messages.datatable.search') }}",
                    zeroRecords: "{{ __('messages.datatable.zero_records') }}",
                    paginate: {
                        first: "{{ __('messages.datatable.first') }}",
                        last: "{{ __('messages.datatable.last') }}",
                        next: "{{ __('messages.datatable.next') }}",
                        previous: "{{ __('messages.datatable.previous') }}"
                    }
                }
            });

            // Status change modal handling
            $('#wallet_transactions_table').on('click', '.change-status', function() {
                var transactionId = $(this).data('id');
                var currentStatus = $(this).data('status');
                
                $('#transaction_id').val(transactionId);
                $('#status').val(currentStatus);
                $('#statusModal').modal('show');
            });

            $('#saveStatus').click(function() {
                var formData = $('#statusForm').serialize();
                
                var transactionId = $('#transaction_id').val();
                $.ajax({
                    url: '/admin/wallet_transactions/' + transactionId + '/update_status',
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        table.ajax.reload();
                        toastr.success('{{ __('messages.wallet.status.update_success') }}');
                    },
                    error: function(xhr) {
                        toastr.error('Error updating status: ' + xhr.responseJSON.message);
                    }
                });
            });
        });
    </script>
@endpush
