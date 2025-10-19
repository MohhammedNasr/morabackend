@extends('layouts.metronic.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Category Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>English Name</label>
                        <p class="form-control-static">{{ $category->name_en }}</p>
                    </div>
                    <div class="form-group">
                        <label>Arabic Name</label>
                        <p class="form-control-static">{{ $category->name_ar }}</p>
                    </div>
                    <div class="form-group">
                        <label>English Description</label>
                        <p class="form-control-static">{{ $category->description_en }}</p>
                    </div>
                    <div class="form-group">
                        <label>Arabic Description</label>
                        <p class="form-control-static">{{ $category->description_ar }}</p>
                    </div>
                </div>
                @if($category->image)
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Image</label>
                        <img src="{{ asset($category->image) }}" class="img-fluid" alt="Category Image">
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                Back to List
            </a>
        </div>
    </div>
</div>
@endsection
