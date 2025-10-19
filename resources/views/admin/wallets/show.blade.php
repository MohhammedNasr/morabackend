@extends('layouts.metronic.base')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Wallet Details</h3>
        <div class="card-toolbar">
            <a href="{{ route('admin.wallets.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> @lang('wallet.buttons.back')
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h4>Wallet Information</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>@lang('wallet.table_headers.id')</th>
                        <td>{{ $wallet->id }}</td>
                    </tr>
                    <tr>
                        <th>@lang('wallet.table_headers.owner')</th>
                        <td>{{ $wallet->user->name }}</td>
                    </tr>
                    <tr>
                        <th>@lang('wallet.table_headers.balance')</th>
                        <td>{{ number_format($wallet->balance, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge badge-{{ $wallet->status === 'active' ? 'success' : 'danger' }}">
                                @lang('wallet.status.' . $wallet->status)
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>@lang('wallet.table_headers.created_at')</th>
                        <td>{{ $wallet->created_at?->format('Y-m-d H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
