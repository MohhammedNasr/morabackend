@extends('layouts.metronic.guest')

@section('content')
<div class="kt-login__body">
    <div class="kt-login__form">
        <div class="kt-login__title">
            <h3>Sign Up</h3>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="font-medium text-red-600">
                    {{ __('Whoops! Something went wrong.') }}
                </div>

                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('store.register') }}" class="kt-form" novalidate="novalidate">
            @csrf

            <div class="form-group">
                <input class="form-control" type="text" placeholder="Name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="form-group">
                <input class="form-control" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <input class="form-control" type="text" placeholder="Phone" name="phone" value="{{ old('phone') }}" required>
            </div>

            <div class="form-group">
                <input class="form-control" type="password" placeholder="Password" name="password" required>
            </div>

            <div class="form-group">
                <input class="form-control" type="password" placeholder="Confirm Password" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <select class="form-control" name="role" required>
                    <option value="">Select Role</option>
                    <option value="store_owner" {{ old('role') == 'store_owner' ? 'selected' : '' }}>Store Owner</option>
                    <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                </select>
            </div>

            <div class="kt-login__actions">
                <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary">Sign Up</button>
            </div>
        </form>

        <div class="kt-login__account">
            <span class="kt-login__account-msg">
                Already have an account?
            </span>
            &nbsp;&nbsp;
            <a href="{{ route('store.login') }}" class="kt-link kt-link--light kt-login__account-link">Sign In</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Add phone number formatting
    $('input[name="phone"]').on('input', function() {
        let phone = $(this).val().replace(/\D/g, '');
        if (phone.length > 0) {
            phone = '+' + phone;
        }
        $(this).val(phone);
    });
</script>
@endpush
