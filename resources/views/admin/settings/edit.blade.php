@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                                <h3 class="kt-subheader__title">@lang('messages.settings.title')</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.dashboard') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">@lang('messages.settings.title')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <form class="kt-form" method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            @if (session('success'))
                <div class="alert alert-success fade show" role="alert">
                    <div class="alert-icon"><i class="flaticon2-check-mark"></i></div>
                    <div class="alert-text">{{ session('success') }}</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true"><i class="la la-close"></i></span>
                        </button>
                    </div>
                </div>
            @endif

            <div class="row">
                <!-- Brand Settings -->
                <div class="col-lg-6">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">@lang('messages.settings.brand_settings')</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>@lang('messages.settings.brand_name') *</label>
                                <input type="text" class="form-control @error('settings.name') is-invalid @enderror" name="settings[name]" value="{{ old('settings.name', $settings['name']->value ?? '') }}" required>
                                @error('settings.name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.logo_path') *</label>
                                <input type="text" class="form-control @error('settings.logo') is-invalid @enderror" name="settings[logo]" value="{{ old('settings.logo', $settings['logo']->value ?? '') }}" required>
                                @error('settings.logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.description')</label>
                                <textarea class="form-control @error('settings.description') is-invalid @enderror" name="settings[description]" rows="3">{{ old('settings.description', $settings['description']->value ?? '') }}</textarea>
                                @error('settings.description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Theme Settings -->
                <div class="col-lg-6">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">@lang('messages.settings.theme_settings')</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>@lang('messages.settings.primary_color') *</label>
                                <div class="input-group">
                                    <input type="color" class="form-control @error('settings.primary_color') is-invalid @enderror" name="settings[primary_color]" value="{{ old('settings.primary_color', $settings['primary_color']->value ?? '#007bff') }}" style="width: 100px;" required>
                                    @error('settings.primary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.secondary_color') *</label>
                                <div class="input-group">
                                    <input type="color" class="form-control @error('settings.secondary_color') is-invalid @enderror" name="settings[secondary_color]" value="{{ old('settings.secondary_color', $settings['secondary_color']->value ?? '#6c757d') }}" style="width: 100px;" required>
                                    @error('settings.secondary_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Settings -->
                <div class="col-lg-12">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">@lang('messages.settings.contact_information')</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('messages.settings.contact_emails') (comma-separated) *</label>
                                        <input type="text" class="form-control @error('settings.emails') is-invalid @enderror" name="settings[emails]" value="{{ old('settings.emails', $settings['emails']->value ?? '') }}" required>
                                        @error('settings.emails')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('messages.settings.contact_phones') (comma-separated) *</label>
                                        <input type="text" class="form-control @error('settings.phone') is-invalid @enderror" name="settings[phone]" value="{{ old('settings.phone', $settings['phone']->value ?? '') }}" required>
                                        @error('settings.phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.address')</label>
                                <textarea class="form-control @error('settings.address') is-invalid @enderror" name="settings[address]" rows="2">{{ old('settings.address', $settings['address']->value ?? '') }}</textarea>
                                @error('settings.address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('messages.settings.latitude') *</label>
                                        <input type="text" class="form-control @error('settings.latitude') is-invalid @enderror" name="settings[latitude]" value="{{ old('settings.latitude', $settings['latitude']->value ?? '') }}" required>
                                        @error('settings.latitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>@lang('messages.settings.longitude') *</label>
                                        <input type="text" class="form-control @error('settings.longitude') is-invalid @enderror" name="settings[longitude]" value="{{ old('settings.longitude', $settings['longitude']->value ?? '') }}" required>
                                        @error('settings.longitude')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media Settings -->
                <div class="col-lg-6">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">@lang('messages.settings.social_media')</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>Facebook</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-facebook"></i></span></div>
                                    <input type="url" class="form-control @error('settings.facebook') is-invalid @enderror" name="settings[facebook]" value="{{ old('settings.facebook', $settings['facebook']->value ?? '') }}">
                                </div>
                                @error('settings.facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Instagram</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-instagram"></i></span></div>
                                    <input type="url" class="form-control @error('settings.instagram') is-invalid @enderror" name="settings[instagram]" value="{{ old('settings.instagram', $settings['instagram']->value ?? '') }}">
                                </div>
                                @error('settings.instagram')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Twitter</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-twitter"></i></span></div>
                                    <input type="url" class="form-control @error('settings.twitter') is-invalid @enderror" name="settings[twitter]" value="{{ old('settings.twitter', $settings['twitter']->value ?? '') }}">
                                </div>
                                @error('settings.twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>YouTube</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-youtube"></i></span></div>
                                    <input type="url" class="form-control @error('settings.youtube') is-invalid @enderror" name="settings[youtube]" value="{{ old('settings.youtube', $settings['youtube']->value ?? '') }}">
                                </div>
                                @error('settings.youtube')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>WhatsApp</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fab fa-whatsapp"></i></span></div>
                                    <input type="tel" class="form-control @error('settings.whatsapp') is-invalid @enderror" name="settings[whatsapp]" value="{{ old('settings.whatsapp', $settings['whatsapp']->value ?? '') }}">
                                </div>
                                @error('settings.whatsapp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Currency Settings -->
                <div class="col-lg-6">
                    <div class="kt-portlet">
                        <div class="kt-portlet__head">
                            <div class="kt-portlet__head-label">
                                <h3 class="kt-portlet__head-title">@lang('messages.settings.business_settings')</h3>
                            </div>
                        </div>
                        <div class="kt-portlet__body">
                            <div class="form-group">
                                <label>@lang('messages.settings.currency_name_en') *</label>
                                <input type="text" class="form-control @error('settings.currency_name_en') is-invalid @enderror" name="settings[currency_name_en]" value="{{ old('settings.currency_name_en', $settings['currency_name_en']->value ?? 'Saudi Riyal') }}" required>
                                @error('settings.currency_name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.currency_name_ar') *</label>
                                <input type="text" class="form-control @error('settings.currency_name_ar') is-invalid @enderror" name="settings[currency_name_ar]" value="{{ old('settings.currency_name_ar', $settings['currency_name_ar']->value ?? 'ريال سعودي') }}" required>
                                @error('settings.currency_name_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.currency_symbol_en') *</label>
                                <input type="text" class="form-control @error('settings.currency_symbol_en') is-invalid @enderror" name="settings[currency_symbol_en]" value="{{ old('settings.currency_symbol_en', $settings['currency_symbol_en']->value ?? 'SAR') }}" required>
                                @error('settings.currency_symbol_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.currency_symbol_ar') *</label>
                                <input type="text" class="form-control @error('settings.currency_symbol_ar') is-invalid @enderror" name="settings[currency_symbol_ar]" value="{{ old('settings.currency_symbol_ar', $settings['currency_symbol_ar']->value ?? 'ر.س') }}" required>
                                @error('settings.currency_symbol_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.tax_rate') (%) *</label>
                                <input type="number" step="0.01" class="form-control @error('settings.tax_rate') is-invalid @enderror" name="settings[tax_rate]" value="{{ old('settings.tax_rate', $settings['tax_rate']->value ?? '15') }}" required>
                                @error('settings.tax_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>@lang('messages.settings.default_payment_terms') (أيام) *</label>
                                <input type="number" class="form-control @error('settings.default_payment_terms') is-invalid @enderror" name="settings[default_payment_terms]" value="{{ old('settings.default_payment_terms', $settings['default_payment_terms']->value ?? '30') }}" required>
                                @error('settings.default_payment_terms')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="kt-portlet">
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">@lang('messages.settings.buttons.save')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- end:: Content -->
@endsection
