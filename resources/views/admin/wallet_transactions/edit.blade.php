@extends('layouts.metronic.admin')

@section('content')
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form method="POST" action="{{ route('admin.wallet_transactions.update', $walletTransaction) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="amount">Transaction Amount</label>
                        <input type="number" class="form-control" id="amount" name="amount" value="{{ $walletTransaction->amount }}" required>
                    </div>
                    <div class="form-group">
                        <label for="wallet_id">Wallet ID</label>
                        <input type="text" class="form-control" id="wallet_id" name="wallet_id" value="{{ $walletTransaction->wallet_id }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Transaction</button>
                </form>
            </div>
        </div>
    </div>
@endsection
