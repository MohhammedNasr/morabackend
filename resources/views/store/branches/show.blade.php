@extends('layouts.metronic.store')

@section('content')
<div class="container">
    <h1>Store Branch Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $branch->name }}</h5>
            <p class="card-text"><strong>Address:</strong> {{ $branch->address }}</p>
            <p class="card-text"><strong>Phone:</strong> {{ $branch->phone }}</p>
            <a href="{{ route('store.branches.edit', $branch->id) }}" class="btn btn-warning">Edit</a>
            <form action="{{ route('store.branches.destroy', $branch->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
