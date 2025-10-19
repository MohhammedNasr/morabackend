@extends('layouts.metronic.store')

@section('title', __('wallet.title'))

@section('content')
    <div class="row">
        <div class="col-xl-8">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">@lang('wallet.balance.current')</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ route('store.wallet.charge') }}" class="btn btn-brand btn-bold btn-sm">
                            <i class="la la-plus"></i> @lang('wallet.actions.charge')
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="kt-widget4">
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__info">
                                        <span class="kt-widget4__title">@lang('wallet.balance.current')</span>
                                        <span class="kt-widget4__text kt-font-brand kt-font-bold">
                                            {{ number_format($wallet ? $wallet->balance : 0, 2) }} SAR
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="kt-widget4">
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__info">
                                        <span class="kt-widget4__title">@lang('wallet.balance.credit_limit')</span>
                                        <span class="kt-widget4__text">
                                            {{ number_format(optional(auth()->user()->store)->credit_limit ?? 0, 2) }} SAR
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="kt-widget4">
                                <div class="kt-widget4__item">
                                    <div class="kt-widget4__info">
                                        <span class="kt-widget4__title">@lang('wallet.balance.available_credit')</span>
                                        <span class="kt-widget4__text kt-font-{{ ((optional(auth()->user()->store)->credit_limit ?? 0) - ($wallet ? $wallet->balance : 0)) >= 0 ? 'success' : 'danger' }}">
                                            {{ number_format((optional(auth()->user()->store)->credit_limit ?? 0) - ($wallet ? $wallet->balance : 0), 2) }} SAR
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xl-4">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">@lang('wallet.actions.quick_actions')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="kt-widget4">
                        <a href="{{ route('store.wallet.transactions') }}" class="btn btn-secondary btn-block mb-3">
                            <i class="la la-list"></i> @lang('wallet.actions.view_transactions')
                        </a>
                        {{-- <a href="{{ route('store.orders.create') }}" class="btn btn-brand btn-block">
                            <i class="la la-cart-plus"></i> @lang('wallet.actions.create_order')
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">@lang('wallet.transactions.title')</h3>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>@lang('wallet.transactions.columns.date')</th>
                                    <th>@lang('wallet.transactions.columns.type')</th>
                                    <th class="text-right">@lang('wallet.transactions.columns.amount')</th>
                                    <th>@lang('wallet.transactions.columns.reference')</th>
                                    <th>@lang('wallet.transactions.columns.status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($transactions->count())
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <span class="kt-badge kt-badge--{{ $transaction->type === 'deposit' ? 'success' : 'warning' }} kt-badge--inline">
                                                    @lang('wallet.transactions.types.' . $transaction->type)
                                                </span>
                                            </td>
                                            <td class="text-right kt-font-bold kt-font-{{ $transaction->type === 'deposit' ? 'success' : 'danger' }}">
                                                {{ $transaction->type === 'deposit' ? '+' : '-' }}{{ number_format($transaction->amount, 2) }} SAR
                                            </td>
                                            <td>{{ $transaction->payment_reference ?? 'N/A' }}</td>
                                            <td>
                                                <span class="kt-badge kt-badge--{{ $transaction->status === 'completed' ? 'success' : 'warning' }} kt-badge--inline">
                                                    @lang('wallet.transactions.statuses.' . $transaction->status)
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">@lang('wallet.transactions.empty')</td>
                                        </tr>
                                    @endforelse
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">@lang('wallet.transactions.empty')</td>
                                    </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
