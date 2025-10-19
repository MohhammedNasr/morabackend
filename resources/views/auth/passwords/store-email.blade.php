@extends('layouts.metronic.guest')

@section('content')
    <div class="kt-login__body">
        <div class="kt-login__form">
            <div class="kt-login__title">
                <h3>Reset Password</h3>
            </div>

            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('store.password.email') }}" class="kt-form">
                @csrf

                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Phone Number" name="phone"
                        value="{{ old('phone') }}" required autofocus>
                    @error('phone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="kt-login__actions">
                    <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary">
                        Send Password Reset Link
                    </button>
                </div>
            </form>

            <div class="kt-login__account">
                <span class="kt-login__account-msg">
                    Remember your password?
                </span>
                &nbsp;&nbsp;
                <a href="{{ route('store.login') }}" class="kt-link kt-link--light kt-login__account-link">
                    Sign In
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Add phone number formatting
        $('input[name="phone"]').on('input', function() {
            let phone = $(this).val().replace(/\D/g, '');

            // Format based on input length
            if (phone.length > 0) {
                if (phone.startsWith('0')) {
                    phone = '+966' + phone.substring(1);
                } else if (phone.startsWith('5')) {
                    phone = '+966' + phone;
                } else if (phone.startsWith('966')) {
                    phone = '+966' + phone;
                }
            }

            $(this).val(phone);
        });
    </script>
@endpush
