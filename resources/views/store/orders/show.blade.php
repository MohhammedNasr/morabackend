@extends('layouts.metronic.base')

@section('title', 'Order Details')

@section('content')
    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <!-- begin:: Portlet -->
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        {{ __('Order Details') }} #{{ $order->id }}
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <!-- Removed duplicate cancel button -->
                </div>
            </div>
            <div class="kt-portlet__body">
                <div class="row">
                    <!-- Order Summary -->
                    <div class="col-md-4">
                        <div class="kt-portlet kt-portlet--height-fluid">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">{{ __('Order Summary') }}</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="kt-widget4">
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__info">
                                            <span class="kt-widget4__title">{{ __('Status') }}</span>
                                            <span class="kt-widget4__text kt-font-{{ $order->status_color }}">
                                                {{ __("messages.store_orders.status.$order->status") }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__info">
                                            <span class="kt-widget4__title">{{ __('Order Date') }}</span>
                                            <span
                                                class="kt-widget4__text">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__info">
                                            <span class="kt-widget4__title">{{ __('Due Date') }}</span>
                                            <span
                                                class="kt-widget4__text">{{ $order->payment_due_date ? $order->payment_due_date->format('Y-m-d') : '-' }}</span>
                                        </div>
                                    </div>
                                    <div class="kt-widget4__item">
                                        <div class="kt-widget4__info">
                                            <span class="kt-widget4__title">{{ __('Total Amount') }}</span>
                                            <span
                                                class="kt-widget4__text kt-font-bold">{{ number_format($order->total_amount, 2) }}
                                                SAR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Order Actions -->
                            <div class="kt-portlet">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ __('Actions') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    @if ($order->status === 'pending')
                                        <form method="POST" action="{{ route('store.orders.verify', $order) }}"
                                            class="mb-3">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-block">
                                                <i class="la la-check"></i> {{ __('Verify Order') }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('store.orders.cancel', $order) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-block"
                                                onclick="return confirm('{{ __('Are you sure you want to cancel this order?') }}')">
                                                <i class="la la-trash"></i> {{ __('Cancel Order') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Order Details -->
                    <div class="col-md-8">
                        <div class="kt-portlet">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">{{ __('Order Items') }}</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                <div class="kt-section">
                                    <div class="kt-section__content">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Product') }}</th>
                                                    <th>{{ __('Quantity') }}</th>
                                                    <th>{{ __('Unit Price') }}</th>
                                                    <th>{{ __('Total') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->items as $item)
                                                    <tr>
                                                        <td>{{ app()->getLocale() === 'ar' ? $item->product->name_ar : $item->product->name_en }}
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->unit_price, 2) }} SAR</td>
                                                        <td>{{ number_format($item->quantity * $item->unit_price, 2) }} SAR
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="3" class="text-right">
                                                        <strong>{{ __('Total') }}:</strong></td>
                                                    <td><strong>{{ number_format($order->total_amount, 2) }} SAR</strong>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supplier Information -->
                        <div class="kt-portlet mt-4">
                            <div class="kt-portlet__head">
                                <div class="kt-portlet__head-label">
                                    <h3 class="kt-portlet__head-title">{{ __('Supplier Information') }}</h3>
                                </div>
                            </div>
                            <div class="kt-portlet__body">
                                @if ($order->subOrders->isNotEmpty())
                                    @foreach ($order->subOrders as $subOrder)
                                        <div class="kt-widget4 mb-4">
                                            <div class="kt-widget4__item">
                                                <div class="kt-widget4__info">
                                                    <span class="kt-widget4__title">{{ __('Supplier') }}
                                                        #{{ $loop->iteration }}</span>
                                                </div>
                                            </div>
                                            <div class="kt-widget4__item">
                                                <div class="kt-widget4__info">
                                                    <span class="kt-widget4__title">{{ __('Name') }}</span>
                                                    <span class="kt-widget4__text">{{ $subOrder->supplier->name }}</span>
                                                </div>
                                            </div>
                                            <div class="kt-widget4__item">
                                                <div class="kt-widget4__info">
                                                    <span class="kt-widget4__title">{{ __('Email') }}</span>
                                                    <span class="kt-widget4__text">{{ $subOrder->supplier->email }}</span>
                                                </div>
                                            </div>
                                            <div class="kt-widget4__item">
                                                <div class="kt-widget4__info">
                                                    <span class="kt-widget4__title">{{ __('Phone') }}</span>
                                                    <span class="kt-widget4__text">{{ $subOrder->supplier->phone }}</span>
                                                </div>
                                            </div>
                                            <div class="kt-widget4__item">
                                                <div class="kt-widget4__info">
                                                    <span class="kt-widget4__title">{{ __('Commercial Record') }}</span>
                                                    <span
                                                        class="kt-widget4__text">{{ $subOrder->supplier->commercial_record }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="alert alert-info">{{ __('No supplier information available') }}</div>
                                @endif
                            </div>
                        </div>

                        @if ($order->rejection_reason)
                            <div class="kt-portlet mt-4">
                                <div class="kt-portlet__head">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">{{ __('Rejection Reason') }}</h3>
                                    </div>
                                </div>
                                <div class="kt-portlet__body">
                                    <div class="alert alert-danger">{{ $order->rejection_reason }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- end:: Portlet -->
        </div>
        <!-- end:: Content -->
    @endsection
