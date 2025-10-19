@extends('layouts.metronic.guest')

@section('content')
    <div class="kt-login__body">
        <div class="kt-login__form">
            <div class="kt-login__title">
                <h3>تسجيل دخول المتجر</h3>
            </div>

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="font-medium text-red-600">
                        {{ __('عفواً! حدث خطأ ما.') }}
                    </div>

                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('store.checklogin') }}" class="kt-form" style="text-align: right;">
                @csrf

                <!-- Phone Number -->
                <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" placeholder="رقم الهاتف" name="phone"
                            value="{{ old('phone') }}" required autofocus
                            style="background: rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.2); color: #000; padding: 1rem; border-radius: 12px;">
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input class="form-control" type="password" placeholder="كلمة المرور" name="password" required
                        autocomplete="current-password"
                        style="background: rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.2); color: #000; padding: 1rem; border-radius: 12px;">
                </div>
            </br>
                <!-- Remember Me -->
                <div class="kt-login__options">
                    <div class="kt-login__remember">
                        <label class="kt-checkbox" style="margin-right: 0; margin-left: 1rem;">
                        <input type="checkbox" name="remember">
                        <span></span>
                        تذكرني
                        </label>
                    </div>
                    <div class="kt-login__forgot">
                        <a href="{{ route('store.password.request') }}" class="kt-link kt-login__link-forgot">
                            نسيت كلمة المرور؟
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="kt-login__actions">
                    <button type="submit" class="btn btn-brand btn-elevate kt-login__btn-primary">
                        تسجيل الدخول
                    </button>
                </div>
            </form>

            <!-- Register Link -->
            {{-- <div class="kt-login__account">
                <span class="kt-login__account-msg">
                    ليس لديك حساب؟
                </span>
                &nbsp;&nbsp;
                <a href="{{ route('store.register') }}" class="kt-link kt-link--light kt-login__account-link">
                    إنشاء حساب
                </a>
            </div> --}}
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        // Add phone number formatting
        $('input[name="phone"]').on('input', function() {
            let phone = $(this).val().replace(/\D/g, '');

            // Format based on input length
            if (phone.length > 0) {
                if (phone.startsWith('0')) {
                    phone = '+966' + phone.substring(1);
                } else if (phone.startsWith('5')) {
                    phone = '+966' + phone;
                } else if (!phone.startsWith('+966')) {
                    phone = '+966' + phone;
                }
            }

            $(this).val(phone);
        });
    </script> --}}
@endpush
