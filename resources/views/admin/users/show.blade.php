@extends('layouts.metronic.base')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">View Admin User</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ $user->phone }}" readonly>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" class="form-control" id="role" name="role" value="{{ $user->role->name }}" readonly>
            </div>
            <div class="form-group">
                <label for="created_at">Created At</label>
                <input type="text" class="form-control" id="created_at" name="created_at" value="{{ $user->created_at->format('Y-m-d H:i') }}" readonly>
            </div>
            <div class="form-group">
                <label for="updated_at">Updated At</label>
                <input type="text" class="form-control" id="updated_at" name="updated_at" value="{{ $user->updated_at->format('Y-m-d H:i') }}" readonly>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@endsection
