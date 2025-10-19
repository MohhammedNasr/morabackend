@extends('layouts.metronic.guest')

@section('content')
    <div class="kt-login__body">
        <div class="kt-login__form"
            style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(20px); border-radius: 20px; padding: 2rem; border: 1px solid rgba(255, 255, 255, 0.2); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);">
            <div class="kt-login__title animate__animated animate__fadeInDown">
                <h3
                    style="font-size: 2rem; font-weight: 700; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                    مرحباً بالموردين!</h3>
                <p class="kt-login__subtitle" style="color: rgba(255,255,255,0.8); margin-top: 0.5rem;">سجل الدخول إلى حساب المورد الخاص بك</p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

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

            <form method="POST" action="{{ route('supplier.login') }}" class="kt-form" novalidate="novalidate" style="text-align: right;">
                @csrf

                <div class="form-group animate__animated animate__fadeInUp animate__delay-1s">
                        <input class="form-control" type="text" placeholder="رقم الهاتف" name="phone"
                        value="{{ old('phone') }}" required autofocus
                        style="background: rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.2); color: #000; padding: 1rem; border-radius: 12px;">
                </div>

                <div class="form-group animate__animated animate__fadeInUp animate__delay-2s">
                    <input class="form-control" type="password" placeholder="كلمة المرور" name="password" required
                        style="background: rgba(0,0,0,0.1); border: 1px solid rgba(0,0,0,0.2); color: #000; padding: 1rem; border-radius: 12px;">
                </div>

                </br>
                <div class="kt-login__extra animate__animated animate__fadeInUp animate__delay-3s" style="display: flex; justify-content: flex-end; gap: 1rem; float: right;">
                    <label class="kt-checkbox" style="color: #333; order: 2; margin: 0;">
                        <input type="checkbox" name="remember">
                        <span></span>
                        تذكرني
                    </label>
                    @if (Route::has('supplier.password.request'))
                        <a href="{{ route('supplier.password.request') }}" class="kt-link kt-login__link-forgot"
                            style="color: rgba(255,255,255,0.8);">
                            نسيت كلمة المرور؟
                        </a>
                    @endif
                </div>

                <div class="kt-login__actions animate__animated animate__fadeInUp animate__delay-4s">
                    <button type="submit" class="btn btn-primary btn-elevate kt-login__btn-primary"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 1rem 2rem; border-radius: 12px; width: 100%; font-weight: 600; letter-spacing: 0.5px; transition: all 0.3s ease;">
                        تسجيل الدخول
                        <i class="fas fa-arrow-left mr-2"></i>
                    </button>
                </div>
            </form>

            @if (Route::has('supplier.register'))
                <div class="kt-login__account animate__animated animate__fadeInUp animate__delay-5s"
                    style="color: rgba(255,255,255,0.8); margin-top: 1.5rem; text-align: center;">
                    <span class="kt-login__account-msg">
                        ليس لديك حساب مورد؟
                    </span>
                    &nbsp;&nbsp;
                    <a href="{{ route('supplier.register') }}" class="kt-link kt-link--light kt-login__account-link"
                        style="color: #667eea; font-weight: 600;">
                        سجل الآن
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
