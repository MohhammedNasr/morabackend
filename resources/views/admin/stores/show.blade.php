@extends('layouts.metronic.base')

@section('content')
    <div class="kt-portlet" @if(app()->getLocale() === 'ar') dir="rtl" style="text-align: right;" @endif>
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        {{ __('store.store_details') }}: {{ $store->name }}
                    </h3>
            </div>
            <div class="kt-portlet__head-toolbar">
                <div class="kt-portlet__head-actions">
                        {{-- <a href="{{ route('admin.stores.import-products', $store) }}" class="btn btn-primary">
                            <i class="la la-file-import"></i> {{ __('store.import_products') }}
                        </a> --}}
                        <a href="{{ route('admin.stores.index') }}" class="btn btn-secondary">
                            <i class="la la-arrow-left"></i> {{ __('store.back_to_stores') }}
                        </a>
                </div>
            </div>
        </div>

        <div class="kt-portlet__body">
            <div class="row">
                <div class="col-md-6">
                    <h4>{{ __('store.store_information') }}</h4>
                    <table class="table table-bordered">
                        <tr>
                            <th>{{ __('store.name') }}</th>
                            <td>{{ $store->name }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('store.description') }}</th>
                            <td>{{ $store->description }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('store.address') }}</th>
                            <td>{{ $store->address }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('store.phone') }}</th>
                            <td>{{ $store->phone }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('store.email') }}</th>
                            <td>{{ $store->email }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('store.commercial_registration') }}</th>
                            <td>{{ $store->commercial_registration }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('store.tax_id') }}</th>
                            <td>{{ $store->tax_id }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Status</th>
                            <td>
                                <span class="kt-badge kt-badge--{{ $store->is_active ? 'success' : 'danger' }}">
                                    {{ $store->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr> --}}
                        <tr>
                            <th>{{ __('store.created_at') }}</th>
                            <td>{{ $store->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h4>{{ __('store.owner_information') }}</h4>
                    @if ($store->owner()->exists())
                        <table class="table table-bordered">
                            <tr>
                                <th>{{ __('store.name') }}</th>
                                <td>{{ $store->owner->first()->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('store.email') }}</th>
                                <td>{{ $store->owner->first()->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('store.phone') }}</th>
                                <td>{{ $store->owner->first()->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('store.status') }}</th>
                                <td>
                                    <span
                                        class="kt-badge kt-badge--{{ $store->owner->first()->is_active ?? false ? 'success' : 'danger' }}">
                                        {{ $store->owner->first()->is_active ?? false ? __('store.active') : __('store.inactive') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('store.verified') }}</th>
                                <td>
                                    <span
                                        class="kt-badge kt-badge--{{ $store->owner->first()->is_verified ?? false ? 'success' : 'danger' }}">
                                        {{ $store->owner->first()->is_verified ?? false ? __('store.verified_status') : __('store.unverified') }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('store.last_login') }}</th>
                                <td>{{ $store->owner->first()->last_login_at ? $store->owner->first()->last_login_at->format('Y-m-d H:i') : 'Never' }}
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-warning">{{ __('store.no_owner_assigned') }}</div>
                    @endif
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h4>{{ __('store.branches') }}</h4>
                @if($store->branches->count())
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ __('store.name') }}</th>
                            <th>{{ __('store.address') }}</th>
                            <th>{{ __('store.phone') }}</th>
                            <th>{{ __('store.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($store->branches as $branch)
                        <tr>
                            <td>{{ $branch->name }}</td>
                            <td>{{ $branch->street_name }}</td>
                            <td>{{ $branch->phone }}</td>
                            <td>
                                <span class="kt-badge kt-badge--{{ $branch->is_active ? 'success' : 'danger' }}">
                                    {{ $branch->is_active ? __('store.active') : __('store.inactive') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-info">{{ __('store.no_branches_found') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
