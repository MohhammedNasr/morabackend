@extends('layouts.metronic.store')

@section('title', __('wallet.transactions.title'))

@section('content')
<div class="row">
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
                                <th>@lang('wallet.transactions.columns.balance_after')</th>
                                <th>@lang('wallet.transactions.columns.reference')</th>
                                <th>@lang('wallet.transactions.columns.status')</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                    <td>{{ number_format($transaction->balance_after, 2) }} SAR</td>
                                    <td>{{ $transaction->reference_number ?? 'N/A' }}</td>
                                    <td>
                                        <span class="kt-badge kt-badge--{{ $transaction->status === 'completed' ? 'success' : 'warning' }} kt-badge--inline">
                                            @lang('wallet.transactions.statuses.' . $transaction->status)
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">@lang('wallet.transactions.empty')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
