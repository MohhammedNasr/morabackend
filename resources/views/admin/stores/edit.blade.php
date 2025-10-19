@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('store.edit_store')</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.stores.index') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.stores.index') }}" class="kt-subheader__breadcrumbs-link">@lang('store.stores')</a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">@lang('store.edit_store')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <div class="row">
            <div class="col-lg-12">
                <!-- Account Information -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">@lang('store.owner_information')</h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                            <form method="POST" action="{{ route('admin.stores.destroy', $store) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('@lang('common.delete_confirmation')')">
                                    <i class="la la-trash"></i> @lang('common.delete')
                                </button>
                            </form>
                        </div>
                    </div>

                    <form class="kt-form" method="POST" action="{{ route('admin.stores.update', $store) }}">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="name">@lang('store.name') *</label>
                                        @php
                                            $owner = $store->owner->first();
                                            $ownerName = $owner ? $owner->name : '';
                                        @endphp
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $ownerName) }}" required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="email">@lang('store.email') *</label>
                                        @php
                                            $ownerEmail = $owner ? $owner->email : '';
                                        @endphp
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $ownerEmail) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="phone">@lang('store.phone') *</label>
                                        @php
                                            $ownerPhone = $owner ? $owner->phone : '';
                                        @endphp
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $ownerPhone) }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="password">@lang('auth.password')</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <span class="form-text text-muted">@lang('auth.leave_password_blank')</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Store Information -->
                        <div class="kt-portlet__body">
                            <div class="kt-portlet__head-label mb-4">
                                <h3 class="kt-portlet__head-title" style="text-align:right;">@lang('store.store_information')</h3>
                            </div>

                            <div class="row">
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label for="type">@lang('store.store_type') *</label>
                                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                            <option value="hypermarket" {{ old('type', $store->type) == 'hypermarket' ? 'selected' : '' }}>@lang('store.hypermarket')</option>
                                            <option value="supermarket" {{ old('type', $store->type) == 'supermarket' ? 'selected' : '' }}>@lang('store.supermarket')</option>
                                            <option value="restaurant" {{ old('type', $store->type) == 'restaurant' ? 'selected' : '' }}>@lang('store.restaurant')</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label for="commercial_record">@lang('store.commercial_registration') *</label>
                                        <input type="text" class="form-control @error('commercial_record') is-invalid @enderror" id="commercial_record" name="commercial_record" value="{{ old('commercial_record', $store->commercial_record) }}" required>
                                        @error('commercial_record')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label for="tax_record">@lang('store.tax_id') *</label>
                                        <input type="text" class="form-control @error('tax_record') is-invalid @enderror" id="tax_record" name="tax_record" value="{{ old('tax_record', $store->tax_record) }}" required>
                                        @error('tax_record')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-4">
                                    <div class="form-group">
                                        <label for="id_number">@lang('store.id_number') *</label>
                                        <input type="text" class="form-control @error('id_number') is-invalid @enderror" id="id_number" name="id_number" value="{{ old('id_number', $store->id_number) }}" required>
                                        @error('id_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="credit_limit">@lang('store.credit_limit') *</label>
                                        <input type="number" min="0" step="0.01" class="form-control @error('credit_limit') is-invalid @enderror" id="credit_limit" name="credit_limit" value="{{ old('credit_limit', $store->credit_limit) }}" required>
                                        @error('credit_limit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                                            <input type="checkbox" name="is_verified" value="1" {{ old('is_verified', $store->is_verified) ? 'checked' : '' }}>
                                            @lang('store.verified_status')
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">@lang('common.update')</button>
                                <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">@lang('common.cancel')</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content -->
@endsection

@push('scripts')
<script>
    // Initialize Metronic's SweetAlert for delete confirmation
    $(document).ready(function() {
        $('form[action$="destroy"]').on('submit', function(e) {
            e.preventDefault();
            const form = this;

            swal.fire({
                title: '@lang('common.are_you_sure')',
                text: "@lang('common.cannot_revert')",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: '@lang('common.yes_delete')',
                cancelButtonText: '@lang('common.no_cancel')',
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
