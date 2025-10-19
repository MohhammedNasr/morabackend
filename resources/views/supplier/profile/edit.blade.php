@extends('layouts.metronic.supplier')

@section('title', __('messages.supplier_dashboard.profile.edit_title'))

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                {{ __('messages.supplier_dashboard.profile.title') }}
            </h3>
        </div>
    </div>
    <form method="post" action="{{ route('supplier.profile.update') }}">
        @csrf
        @method('PUT')
        <div class="kt-portlet__body">
            <div class="form-group">
                <label>{{ __('auth.name') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $supplier?->name ?? '') }}" required autofocus>
                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('auth.email') }}</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $supplier?->user?->email ?? '') }}" required>
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('auth.commercial_record') }}</label>
                <input type="text" name="commercial_record" class="form-control" value="{{ old('commercial_record', $supplier->commercial_record) }}" required>
                @error('commercial_record') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('messages.supplier_dashboard.profile.payment_term') }}</label>
                <input type="number" name="payment_term_days" class="form-control" value="{{ old('payment_term_days', $supplier->payment_term_days) }}" min="1" max="365" required>
                @error('payment_term_days') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('messages.supplier_dashboard.profile.current_password') }}</label>
                <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                @error('current_password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('messages.supplier_dashboard.profile.new_password') }}</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password">
                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label>{{ __('messages.supplier_dashboard.profile.confirm_password') }}</label>
                <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                @error('password_confirmation') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="kt-portlet__foot">
            <button type="submit" class="btn btn-primary">{{ __('auth.save') }}</button>
            @if (session('success'))
                <span class="text-success ml-3">{{ session('success') }}</span>
            @endif
        </div>
    </form>
</div>
@endsection
