@extends('layouts.metronic.admin')

@section('content')
    <div @if (app()->getLocale() === 'ar') dir="rtl" @endif>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title @if (app()->getLocale() === 'ar') text-right @else text-left @endif">@lang('admin/suppliers.show.title')
                </h3>
                {{-- <div class="card-toolbar">
            <a href="{{ route('admin.suppliers.import-products', $supplier) }}" class="btn btn-primary">
                <i class="fas fa-file-import"></i> @lang('admin/suppliers.show.import_products')
            </a>
        </div>  --}}
            </div>
            <div class="card-body" @if (app()->getLocale() === 'ar') style="text-align:right;" @endif>
                <div class="row" @if (app()->getLocale() === 'ar') style="text-align:right;" @endif>
                    <div class="col-md-6">
                        <h4 class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">@lang('admin/suppliers.show.basic_info')
                        </h4>
                        <table class="table table-bordered">
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.name')</th>
                                <td>{{ $supplier->name }}</td>
                            </tr>
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.contact_name')</th>
                                <td>{{ $supplier->contact_name }}</td>
                            </tr>
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.email')</th>
                                <td>{{ $supplier->email }}</td>
                            </tr>
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.phone')</th>
                                <td>{{ $supplier->phone }}</td>
                            </tr>
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.address')</th>
                                <td>{{ $supplier->address }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4 class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">@lang('admin/suppliers.show.business_info')
                        </h4>
                        <table class="table table-bordered">
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.commercial_record')</th>
                                <td>{{ $supplier->commercial_record }}</td>
                            </tr>
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.payment_terms')</th>
                                <td>{{ $supplier->payment_term_days }} days</td>
                            </tr>
                            <tr>
                                <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                    @lang('admin/suppliers.show.fields.status')</th>
                                <td>
                                    @if ($supplier->is_active)
                                        <span class="badge badge-success">@lang('admin/suppliers.show.fields.active')</span>
                                    @else
                                        <span class="badge badge-danger">@lang('admin/suppliers.show.fields.inactive')</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h4 class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">@lang('admin/suppliers.show.products')
                        </h4>
                        @if ($supplier->products->count() > 0)
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                            @lang('admin/suppliers.show.fields.product_name')</th>
                                        <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                            @lang('admin/suppliers.show.fields.sku')</th>
                                        <th class="@if (app()->getLocale() === 'ar') text-right @else text-left @endif">
                                            @lang('admin/suppliers.show.fields.price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supplier->products as $product)
                                        <tr>
                                            <td>{{ $product->{'name_' . app()->getLocale()} }}</td>
                                            <td>{{ $product->sku }}</td>
                                            <td>{{ $product->price }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">@lang('admin/suppliers.show.no_products')</div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ route('admin.suppliers.index', ['locale' => app()->getLocale()]) }}"
                    class="btn btn-secondary">@lang('admin/suppliers.show.back')</a>
            </div>
        </div>
    </div>
@endsection
