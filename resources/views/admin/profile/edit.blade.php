@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title @if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.title') }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.dashboard') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">{{ __('admin/profile.breadcrumbs.profile_settings') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-6">
                <!-- Profile Information -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title @if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.profile_information') }}</h3>
                        </div>
                    </div>

                    <form class="kt-form" method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ __(session('success')) }}
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="name" class="@if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.name') }} *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ __($message) }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="@if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.email') }} *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="phone" class="@if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.phone') }} *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">{{ __('admin/profile.save_changes') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <!-- Update Password -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title @if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.update_password') }}</h3>
                        </div>
                    </div>

                    <form class="kt-form" method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label for="current_password" class="@if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.current_password') }}</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="@if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="@if(app()->getLocale() === 'ar') text-right @else text-left @endif">{{ __('admin/profile.password_confirmation') }}</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">{{ __('admin/profile.update_password') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection
