@extends('layouts.metronic.base')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create Wallet</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.wallets.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="user_id" class="form-label">@lang('wallet.labels.owner')</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="balance" class="form-label">@lang('wallet.labels.balance')</label>
                <input type="number" step="0.01" class="form-control" id="balance" name="balance" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">@lang('wallet.labels.status')</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active">@lang('wallet.status.active')</option>
                    <option value="inactive">@lang('wallet.status.inactive')</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">@lang('wallet.buttons.create')</button>
        </form>
    </div>
</div>
@endsection
