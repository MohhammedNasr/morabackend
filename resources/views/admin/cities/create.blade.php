@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New City</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.cities.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name_ar">Name (Arabic)</label>
                <input type="text" name="name_ar" id="name_ar" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name_en">Name (English)</label>
                <input type="text" name="name_en" id="name_en" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" name="code" id="code" class="form-control" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('admin.cities.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
