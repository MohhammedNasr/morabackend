@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('orders.order_details_title', ['number' => $order->reference_number])</h3>
                <div class="kt-subheader__group">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <i class="la la-arrow-left"></i> @lang('orders.back')
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid"
        @if (app()->getLocale() === 'ar') dir="rtl" style="text-align: right;" @endif>
        <!-- Order Details -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">@lang('orders.order_details')</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>@lang('orders.store_info')</h5>
                                <div class="kt-list-timeline">
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--success"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.store'):</strong> {{ $order->store->name }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--info"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.contact'):</strong>
                                            {{ $order->store->users->first()->name ?? __('orders.not_available') }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--brand"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.phone'):</strong>
                                            {{ $order->store->users->first()->phone ?? __('orders.not_available') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>@lang('orders.order_info')</h5>
                                <div class="kt-list-timeline">
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--danger"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.status_label'):</strong> @include('admin.orders.partials.status')
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--warning"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.created_at'):</strong>
                                            {{ $order->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--brand"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.reference_number'):</strong> {{ $order->reference_number }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--info"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.store_branch'):</strong>
                                            {{ $order->store_branch ? $order->store_branch->name : __('messages.not_available') }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--success"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.sub_total'):</strong> {{ __('orders.currency_symbol') }}
                                            {{ number_format($order->sub_total, 2) }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--warning"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.discount'):</strong> {{ __('orders.currency_symbol') }}
                                            {{ number_format($order->discount, 2) }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--primary"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.promotion'):</strong>
                                            {{ $order->promotion ? $order->promotion->name : __('messages.not_available') }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--success"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.total'):</strong> {{ __('orders.currency_symbol') }}
                                            {{ number_format($order->total_amount, 2) }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--primary"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.payment_due'):</strong>
                                            {{ $order->payment_due_date->format('Y-m-d') }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--info"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.products_count'):</strong> {{ $order->products_count }}
                                        </span>
                                    </div>
                                    <div class="kt-list-timeline__item">
                                        <span class="kt-list-timeline__badge kt-list-timeline__badge--brand"></span>
                                        <span class="kt-list-timeline__text">
                                            <strong>@lang('orders.verification'):</strong>
                                            @if ($order->verified_at)
                                                @lang('orders.verified_at', ['date' => $order->verified_at->format('Y-m-d H:i')])
                                            @else
                                                @lang('orders.not_verified')
                                            @endif
                                        </span>
                                    </div>
                                    @if ($order->cancellation_reason)
                                        <div class="kt-list-timeline__item">
                                            <span class="kt-list-timeline__badge kt-list-timeline__badge--danger"></span>
                                            <span class="kt-list-timeline__text">
                                                <strong>@lang('orders.cancellation_reason'):</strong> {{ $order->cancellation_reason }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub-Orders -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">@lang('messages.orders.sections.sub_orders')</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="table-responsive">
                            <table class="table table-striped table-sm" style="font-size: 0.9rem;">
                                <thead>
                                    <tr>
                                        <th>@lang('suborders.table_headers.reference')</th>
                                        <th>@lang('suborders.table_headers.supplier')</th>
                                        <th>@lang('suborders.table_headers.contact')</th>
                                        <th>@lang('suborders.table_headers.status')</th>
                                        <th>@lang('suborders.table_headers.items')</th>
                                        <th>@lang('suborders.table_headers.unit_price')</th>
                                        <th>@lang('suborders.table_headers.quantity')</th>
                                        <th>@lang('suborders.table_headers.total')</th>
                                        <th>@lang('suborders.table_headers.payment_status')</th>
                                        <th>@lang('suborders.table_headers.delivery_status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->subOrders as $subOrder)
                                        @foreach ($subOrder->items as $item)
                                            <tr>
                                                <td>{{ $subOrder->reference_number }}</td>
                                                <td>{{ $subOrder->supplier->name }}</td>
                                                <td>
                                                    {{ $subOrder->supplier->phone }}<br>
                                                    {{ $subOrder->supplier->email }}
                                                </td>
                                                <td>@include('admin.orders.partials.suborder_status', [
                                                    'order' => $subOrder,
                                                ])</td>
                                                <td>{{ $item->product->name }}</td>
                                                <td>{{ __('messages.currency_symbol') }}
                                                    {{ number_format($item->unit_price, 2) }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ __('messages.currency_symbol') }}
                                                    {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                                <td>
                                                    <span class="kt-badge kt-badge--{{ $subOrder->is_paid ? 'success' : 'danger' }} d-flex align-items-center justify-content-center" style="min-width: 80px;">
                                                        {{ $subOrder->is_paid ? __('suborders.payment.paid') : __('suborders.payment.pending') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($subOrder->status == 'delivered')
                                                        @if ($subOrder->delivery_date->isPast())
                                                            <span class="kt-badge kt-badge--success d-flex align-items-center justify-content-center" style="min-width: 80px;">@lang('suborders.delivery.delivered')</span>
                                                        @else
                                                            <span class="kt-badge kt-badge--warning d-flex align-items-center justify-content-center" style="min-width: 80px;">@lang('suborders.delivery.scheduled')</span>
                                                        @endif
                                                        <br>{{ $subOrder->delivery_date->format('Y-m-d') }}
                                                    @else
                                                        <span class="kt-badge kt-badge--danger d-flex align-items-center justify-content-center" style="min-width: 80px;">@lang('suborders.delivery.not_delivered')</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        {{-- <td>
                                                <a href="{{ route('admin.orders.sub-orders.show', ['order' => $order->id, 'subOrder' => $subOrder->id]) }}"
                                                   class="btn btn-sm btn-clean btn-icon btn-icon-md"
                                                   title="@lang('orders.view_details')">
                                                    <i class="la la-eye"></i>
                                                </a>
                                                {{-- @include('admin.orders.partials.actions', ['order' => $subOrder, 'isSubOrder' => true])
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="kt-portlet mt-4">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('orders.payment_info') }}</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        @if ($order->payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" style="font-size: 0.9rem;">
                                    <thead>
                                        <tr>
                                            <th>@lang('orders.payment_name')</th>
                                            <th>@lang('orders.payment_due_date')</th>
                                            <th>@lang('orders.amount')</th>
                                            <th>@lang('orders.payment_status')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->notes ?? $payment->method }}</td>
                                                <td>{{ $payment->due_date ? $payment->due_date->format('Y-m-d') : __('messages.not_set') }}
                                                </td>
                                                <td>{{ __('messages.currency_symbol') }}
                                                    {{ number_format($payment->amount, 2) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="kt-badge kt-badge--{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'due_to_pay' ? 'warning' : 'danger') }} d-flex align-items-center justify-content-center" style="min-width: 80px;">
                                                            {{ __('orders.payment_status_list.' . $payment->status) }}
                                                        </span>
                                                        <button class="btn btn-sm btn-icon btn-warning ml-2 change-payment-status"
                                                            data-id="{{ $payment->id }}"
                                                            data-status="{{ $payment->status }}"
                                                            title="{{ __('orders.change_payment_status') }}">
                                                            <i class="la la-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>{{ __('orders.no_payments_found') }}</p>
                        @endif
                    </div>
                </div>
            </div>
                {{-- <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">@lang('messages.orders.sections.actions')</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        @include('admin.orders.partials.actions')
                        <div class="mt-4">
                            <a href="{{ route('admin.orders.download-invoice', $order) }}"
                               class="btn btn-brand btn-elevate btn-icon-sm">
                                <i class="la la-file-pdf"></i> @lang('messages.orders.buttons.download_invoice')
                            </a>
                        </div>
                    </div>
                </div> --}}

                <!-- Timeline -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('orders.order_timeline') }}</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        <div class="kt-timeline-v2">
                            @foreach ($order->timeline ?? [] as $event)
                                <div class="kt-timeline-v2__item">
                                    <span
                                        class="kt-timeline-v2__item-time">{{ $event->created_at->format('Y-m-d H:i') }}</span>
                                    <div class="kt-timeline-v2__item-cricle">
                                        <i class="fa fa-genderless"></i>
                                    </div>
                                    <div class="kt-timeline-v2__item-text">
                                        <p>
                                            @if (array_key_exists($event->event, __('suborders.timeline')))
                                                {{ __("suborders.timeline.{$event->event}") }}
                                            @else
                                                {{ $event->event }}
                                            @endif
                                            @if ($event->created_by)
                                                <small class="text-muted">({{ __('orders.by') }}
                                                    {{ $event->createdBy->name ?? $event->created_by }})</small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">{{ __('orders.payment_info') }}</h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">
                        @if ($order->payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-sm" style="font-size: 0.9rem;">
                                    <thead>
                                        <tr>
                                            <th>@lang('orders.payment_name')</th>
                                            <th>@lang('orders.payment_due_date')</th>
                                            <th>@lang('orders.amount')</th>
                                            <th>@lang('orders.payment_status')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($order->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->notes ?? $payment->method }}</td>
                                                <td>{{ $payment->due_date ? $payment->due_date->format('Y-m-d') : __('messages.not_set') }}
                                                </td>
                                                <td>{{ __('messages.currency_symbol') }}
                                                    {{ number_format($payment->amount, 2) }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span class="kt-badge kt-badge--{{ $payment->status === 'paid' ? 'success' : ($payment->status === 'due_to_pay' ? 'warning' : 'danger') }} d-flex align-items-center justify-content-center" style="min-width: 80px;">
                                                            {{ __('orders.payment_status_list.' . $payment->status) }}
                                                        </span>
                                                        <button class="btn btn-sm btn-icon btn-warning ml-2 change-payment-status"
                                                            data-id="{{ $payment->id }}"
                                                            data-status="{{ $payment->status }}"
                                                            title="{{ __('orders.change_payment_status') }}">
                                                            <i class="la la-edit"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>{{ __('orders.no_payments_found') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection

@push('styles')
    <link href="{{ asset('metronic_theme/assets/vendors/custom/timeline/timeline-v2.css') }}" rel="stylesheet"
        type="text/css" />
@endpush

<!-- Payment Status Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" role="dialog" aria-labelledby="paymentStatusModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentStatusModalLabel">{{ __('orders.change_payment_status') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="paymentStatusForm">
                    @csrf
                    <input type="hidden" name="payment_id" id="payment_id">
                    <div class="form-group">
                        <label for="payment_status">{{ __('orders.payment_status') }}</label>
                        <select class="form-control" id="payment_status" name="status">
                            <option value="paid">{{ __('orders.payment_status_list.paid') }}</option>
                            <option value="due_to_pay">{{ __('orders.payment_status_list.due_to_pay') }}</option>
                            <option value="failed">{{ __('orders.payment_status_list.failed') }}</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="savePaymentStatus">{{ __('messages.common.save_changes') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle payment status change button click
            $('.change-payment-status').click(function() {
                const paymentId = $(this).data('id');
                const currentStatus = $(this).data('status');

                $('#payment_id').val(paymentId);
                $('#payment_status').val(currentStatus);
                $('#paymentStatusModal').modal('show');
            });

            // Handle payment status form submission
            $('#savePaymentStatus').click(function() {
                const formData = $('#paymentStatusForm').serialize();

                $.ajax({
                    url: "{{ route('admin.orders.payments.update_status') }}",
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        $('#paymentStatusModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Error updating payment status: ' + xhr.responseJSON.message);
                    }
                });
            });

            // Handle order delete form submission
            $('form[method="POST"][onsubmit*="confirm"]').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);

                if (confirm(form.attr('data-confirm') || form.attr('onsubmit').match(/confirm\('([^']+)'/)[
                        1])) {
                    $.ajax({
                        url: form.attr('action'),
                        method: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            window.location.href = response.redirect ||
                                "{{ route('admin.orders.index') }}";
                        },
                        error: function(xhr) {
                            const error = xhr.responseJSON?.message || 'An error occurred';
                            alert(error);
                        }
                    });
                }
            });
        });
    </script>
@endpush
