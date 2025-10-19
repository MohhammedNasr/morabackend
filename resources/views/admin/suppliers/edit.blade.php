@extends('layouts.metronic.admin')

@section('content')
    <!-- begin:: Content Head -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-container kt-container--fluid">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">@lang('suppliers.edit')</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    <a href="{{ route('admin.suppliers.index') }}" class="kt-subheader__breadcrumbs-home">
                        <i class="flaticon2-shelter"></i>
                    </a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <a href="{{ route('admin.suppliers.index') }}" class="kt-subheader__breadcrumbs-link">@lang('suppliers.suppliers')</a>
                    <span class="kt-subheader__breadcrumbs-separator"></span>
                    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">@lang('suppliers.edit')</span>
                </div>
            </div>
        </div>
    </div>
    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container kt-container--fluid kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Account Information -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">@lang('suppliers.account_information')</h3>
                        </div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="kt-portlet__head-toolbar">
                            <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('@lang('suppliers.delete_confirmation')')">
                                    <i class="la la-trash"></i> @lang('suppliers.delete')
                                </button>
                            </form>
                        </div>
                    </div>

                    <form class="kt-form" method="POST" action="{{ route('admin.suppliers.update', $supplier) }}">
                        @csrf
                        @method('PUT')

                        <div class="kt-portlet__body">
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="name">@lang('suppliers.name') *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name', $supplier->name) }}"
                                            required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="email">@lang('suppliers.email') *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $supplier->email) }}"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="phone">@lang('suppliers.phone') *</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="password">@lang('suppliers.password')</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            id="password" name="password" placeholder="Enter new password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <span class="form-text text-muted">@lang('suppliers.password_hint')</span>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">@lang('suppliers.password_confirmation')</label>
                                        <input type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation"
                                            placeholder="Confirm new password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supplier Information -->
                        <div class="kt-portlet__body">
                            <div class="kt-portlet__head-label mb-4">
                                <h3 class="kt-portlet__head-title">@lang('suppliers.supplier_information')</h3>
                            </div>

                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="commercial_record">@lang('suppliers.commercial_record') *</label>
                                        <input type="text"
                                            class="form-control @error('commercial_record') is-invalid @enderror"
                                            id="commercial_record" name="commercial_record"
                                            value="{{ old('commercial_record', $supplier->commercial_record) }}" required>
                                        @error('commercial_record')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="payment_term_days">@lang('suppliers.payment_term_days') *</label>
                                        <input type="number" min="0"
                                            class="form-control @error('payment_term_days') is-invalid @enderror"
                                            id="payment_term_days" name="payment_term_days"
                                            value="{{ old('payment_term_days', $supplier->payment_term_days) }}" required>
                                        @error('payment_term_days')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="contact_name">@lang('suppliers.contact_name') *</label>
                                        <input type="text" class="form-control @error('contact_name') is-invalid @enderror"
                                            id="contact_name" name="contact_name"
                                            value="{{ old('contact_name', $supplier->contact_name) }}" required>
                                        @error('contact_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="tax_id">@lang('suppliers.tax_id') *</label>
                                        <input type="text" class="form-control @error('tax_id') is-invalid @enderror"
                                            id="tax_id" name="tax_id"
                                            value="{{ old('tax_id', $supplier->tax_id) }}" required>
                                        @error('tax_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label for="address">@lang('suppliers.address') *</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" required>{{ old('address', $supplier->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="bank_account">@lang('suppliers.bank_account')</label>
                                        <input type="text" class="form-control @error('bank_account') is-invalid @enderror"
                                            id="bank_account" name="bank_account"
                                            value="{{ old('bank_account', $supplier->bank_account) }}">
                                        @error('bank_account')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="form-group">
                                        <label for="website">@lang('suppliers.website')</label>
                                        <input type="url" class="form-control @error('website') is-invalid @enderror"
                                            id="website" name="website"
                                            value="{{ old('website', $supplier->website) }}">
                                        @error('website')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="form-group">
                                        <label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                                            @lang('suppliers.active')
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <button type="submit" class="btn btn-success">@lang('suppliers.update')</button>
                                <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">@lang('suppliers.cancel')</a>
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
                    title: '@lang('suppliers.delete_confirm_title')',
                    text: "@lang('suppliers.delete_confirm_text')",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '@lang('suppliers.delete_confirm_yes')',
                    cancelButtonText: '@lang('suppliers.delete_confirm_no')',
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
