@extends('layouts.metronic.guest')

@section('content')
    <div class="kt-login__body">
        <div class="kt-login__form">
            <div class="kt-login__title">
                <h3>Store Registration</h3>
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

            <form method="POST" action="{{ route('store.register') }}" class="kt-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Full Name" name="name"
                        value="{{ old('name') }}" required autofocus>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Phone Number" name="phone"
                        value="{{ old('phone') }}" required>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input class="form-control" type="password" placeholder="Password" name="password" required
                        autocomplete="new-password">
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <input class="form-control" type="password" placeholder="Confirm Password" name="password_confirmation"
                        required autocomplete="new-password">
                </div>

                <!-- Submit Button -->
                <div class="kt-login__actions">
                    <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary">
                        Register
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="kt-login__account">
                <span class="kt-login__account-msg">
                    Already have an account?
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
