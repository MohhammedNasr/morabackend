@extends('layouts.metronic.base')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Wallet</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.wallets.update', $wallet->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $wallet->user_id === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="balance" class="form-label">@lang('wallet.labels.balance')</label>
                <input type="number" step="0.01" class="form-control" id="balance" name="balance" value="{{ $wallet->balance }}" required>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" {{ $wallet->status === 'active' ? 'selected' : '' }}>@lang('wallet.status.active')</option>
                    <option value="inactive" {{ $wallet->status === 'inactive' ? 'selected' : '' }}>@lang('wallet.status.inactive')</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Wallet</button>
        </form>
    </div>
</div>
@endsection
