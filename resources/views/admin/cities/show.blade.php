@extends('admin.layouts.app')

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">City Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.cities.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Basic Information</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name (English)</th>
                                    <td>{{ $city->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>Name (Arabic)</th>
                                    <td>{{ $city->name_ar }}</td>
                                </tr>
                                <tr>
                                    <th>Code</th>
                                    <td>{{ $city->code }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Areas</h4>
                            @if($city->areas->count() > 0)
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name (English)</th>
                                            <th>Name (Arabic)</th>
                                            <th>Code</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($city->areas as $area)
                                            <tr>
                                                <td>{{ $area->name_en }}</td>
                                                <td>{{ $area->name_ar }}</td>
                                                <td>{{ $area->code }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-info">No areas found for this city</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
