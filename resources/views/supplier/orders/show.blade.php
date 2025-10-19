@extends('layouts.metronic.supplier')

@section('content')
@php
    $isRTL = app()->getLocale() === 'ar';

@endphp

@isset($subOrder)
<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-container kt-container--fluid">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">@lang('messages.orders.details.title', ['number' => $subOrder->reference_number])</h3>
        </div>
    </div>
</div>

<div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid" @if($isRTL) dir="rtl" style="text-align: right;" @endif>
    <!-- Suborder Details Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">@lang('messages.orders.details.title', ['number' => $subOrder->reference_number])</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">@lang('messages.orders.details.suborder_info')</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget kt-widget--general-1">
                                        <div class="kt-widget__wrapper">
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.reference_number')</span>
                                                <span class="kt-widget__value">{{ $subOrder->reference_number }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.status')</span>
                                                <span class="kt-widget__value">
                                                    <span class="kt-badge kt-badge--{{ $subOrder->status === 'pending' ? 'warning' : ($subOrder->status === 'approved' ? 'success' : 'danger') }} kt-badge--inline kt-badge--pill" style="white-space: nowrap; padding: 0.5rem 1rem; font-size: 1rem;">
                                                        {{ trans("suborders.status.$subOrder->status") }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.created_at')</span>
                                                <span class="kt-widget__value">{{ $subOrder->created_at ? $subOrder->created_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.updated_at')</span>
                                                <span class="kt-widget__value">{{ $subOrder->updated_at ? $subOrder->updated_at->format('Y-m-d H:i') : 'N/A' }}</span>
                                            </div>
                                            @if($subOrder->rejection_reason)
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.rejection_reason')</span>
                                                <span class="kt-widget__value">{{ $subOrder->rejection_reason }}</span>
                                            </div>
                                            @endif
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.delivered_at')</span>
                                                <span class="kt-widget__value">
                                                    {{ $subOrder->delivered_at ? $subOrder->delivered_at->format('Y-m-d H:i') : __('messages.orders.details.not_delivered') }}
                                                </span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.total_amount')</span>
                                                <span class="kt-widget__value">{{ number_format($subOrder->total, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kt-portlet kt-portlet--height-fluid">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">@lang('messages.orders.details.store_info')</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="kt-widget kt-widget--general-1">
                                        <div class="kt-widget__wrapper">
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.store')</span>
                                                <span class="kt-widget__value">{{ $subOrder->order && $subOrder->order->store ? $subOrder->order->store->name : 'N/A' }}</span>
                                            </div>
                                            @if($subOrder->order && $subOrder->order->branch)
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.branch')</span>
                                                <span class="kt-widget__value">{{ $subOrder->order->branch->name }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.branch_phone')</span>
                                                <span class="kt-widget__value">{{ $subOrder->order->branch->phone ?? 'N/A' }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.branch_email')</span>
                                                <span class="kt-widget__value">{{ $subOrder->order->branch->email ?? 'N/A' }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.branch_manager')</span>
                                                <span class="kt-widget__value">{{ $subOrder->order->branch->manager_name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.branch_manager_phone')</span>
                                                <span class="kt-widget__value">{{ $subOrder->order->branch->manager_phone ?? 'N/A' }}</span>
                                            </div>
                                            @endif
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.sub_total')</span>
                                                <span class="kt-widget__value">{{ number_format($subOrder->sub_total, 2) }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.discount')</span>
                                                <span class="kt-widget__value">{{ number_format($subOrder->discount, 2) }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.total_amount')</span>
                                                <span class="kt-widget__value">{{ number_format($subOrder->total_amount, 2) }}</span>
                                            </div>
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.address')</span>
                                                <span class="kt-widget__value">
                                                    @if($subOrder->order && $subOrder->order->branch && $subOrder->order->branch->address)
                                                        {{ $subOrder->order->branch->address }}
                                                    @elseif($subOrder->order && $subOrder->order->store && $subOrder->order->store->address)
                                                        {{ $subOrder->order->store->address }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </span>
                                            </div>
                                            @if($subOrder->order && $subOrder->order->branch && ($subOrder->order->branch->latitude || $subOrder->order->branch->longitude))
                                            <div class="kt-widget__label">
                                                <span class="kt-widget__desc">@lang('messages.orders.details.location')</span>
                                                <span class="kt-widget__value">
                                                    <a href="https://maps.google.com/?q={{ $subOrder->order->branch->latitude }},{{ $subOrder->order->branch->longitude }}" target="_blank">
                                                        @lang('messages.orders.details.view_on_map')
                                                    </a>
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">@lang('messages.orders.details.products')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    @if($subOrder->items && count($subOrder->items) > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>@lang('messages.orders.details.image')</th>
                                    <th>@lang('messages.orders.details.sku')</th>
                                    <th>@lang('messages.orders.details.product')</th>
                                    <th>@lang('messages.orders.details.quantity')</th>
                                    <th>@lang('messages.orders.details.price')</th>
                                    <th>@lang('messages.orders.details.total')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($subOrder->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product && $item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" width="50">
                                        @else
                                            <span>N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->product->sku ?? 'N/A' }}</td>
                                    <td>{{ $item->product->{'name_'.app()->getLocale()} ?? 'N/A' }}</td>
                                    <td @if($isRTL) class="text-left" @else class="text-right" @endif>{{ $item->quantity }}</td>
                                    <td @if($isRTL) class="text-left" @else class="text-right" @endif>{{ number_format($item->price, 2) }}</td>
                                    <td @if($isRTL) class="text-left" @else class="text-right" @endif>{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                                    <p>@lang('messages.orders.details.no_products')</p>
                    @endif
                </div>
            </div>
        </div>

    <!-- Actions Section -->
    <div class="row">
        <div class="col-lg-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">@lang('messages.orders.details.actions_title')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    @if($subOrder->status === \App\Models\SubOrder::STATUS_PENDING)
                    <div class="kt-form__actions text-center">
                        <form action="{{ route('supplier.orders.approve', ['subOrder' => $subOrder]) }}" method="POST" class="d-inline-block @if($isRTL) ml-3 @else mr-3 @endif">
                            @csrf
                            <input type="hidden" name="status" value="{{ \App\Models\SubOrder::STATUS_ACCEPTED_BY_SUPPLIER }}">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="la la-check"></i> @lang('messages.orders.details.actions.accept_by_supplier')
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#rejectModal">
                                <i class="la la-close"></i> @lang('messages.orders.details.actions.reject_by_supplier')
                        </button>
                    </div>
                    @endif

                    @if($subOrder->status === \App\Models\SubOrder::STATUS_ACCEPTED_BY_SUPPLIER)
                    <div class="kt-form__actions text-center">
                        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#assignRepModal">
                                <i class="la la-user"></i> @lang('messages.orders.details.actions.assign_representative')
                        </button>
                        <form action="{{ route('supplier.orders.deliver', ['subOrder' => $subOrder]) }}" method="POST" class="d-inline-block">
                            <input type="hidden" name="status" value="{{ \App\Models\SubOrder::STATUS_DELIVERED }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="la la-truck"></i> @lang('messages.orders.details.actions.deliver')
                            </button>
                        </form>
                    </div>
                    @endif

<!-- Assign Representative Modal -->
<div class="modal fade" id="assignRepModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('supplier.sub-orders.assign-representative', ['subOrder' => $subOrder]) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">@lang('messages.orders.details.modal.assign_representative')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.orders.details.modal.select_representative')</label>
                        <select class="form-control" name="representative_id" required>
                            <option value="">@lang('messages.orders.details.modal.select_representative_placeholder')</option>
                            @foreach(auth()->guard('supplier-web')->user()->representatives as $rep)
                                <option value="{{ $rep->id }}">{{ $rep->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        @lang('messages.orders.details.actions.cancel')
                    </button>
                    <button type="submit" class="btn btn-primary">
                        @lang('messages.orders.details.actions.assign')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('supplier.orders.reject', ['subOrder' => $subOrder]) }}" method="POST">
                <input type="hidden" name="status" value="{{ \App\Models\SubOrder::STATUS_REJECTED_BY_SUPPLIER }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">@lang('messages.orders.details.modal.reject_title')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('messages.orders.details.modal.reason_label')</label>
                        <textarea class="form-control" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        @lang('messages.orders.details.actions.cancel')
                    </button>
                    <button type="submit" class="btn btn-danger">
                        @lang('messages.orders.details.actions.confirm_rejection')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endisset
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize any needed scripts here
    });
</script>
@endpush
