@extends('layouts.metronic.guest')

@section('content')
<div class="kt-login__body">
    <div class="kt-login__form">
        <div class="kt-login__title">
            <h3>Verify Phone Number</h3>
        </div>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, please verify your phone number by entering the verification code we just sent to your phone.') }}
        </div>

        @if (session('status') == 'verification-code-sent')
            <div class="alert alert-success" role="alert">
                {{ __('A new verification code has been sent to your phone number.') }}
            </div>
        @endif

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

        <form method="POST" action="{{ route('verification.verify') }}" class="kt-form" novalidate="novalidate">
            @csrf

            <div class="form-group">
                <input class="form-control" type="text" placeholder="Verification Code" name="code" required autofocus>
            </div>

            <div class="kt-login__actions">
                <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary">
                    {{ __('Verify') }}
                </button>

                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link kt-link">
                        {{ __('Resend Verification Code') }}
                    </button>
                </form>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-light-primary">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Format verification code input to show only numbers
    $('input[name="code"]').on('input', function() {
        $(this).val($(this).val().replace(/\D/g, ''));
    });
</script>
@endpush
