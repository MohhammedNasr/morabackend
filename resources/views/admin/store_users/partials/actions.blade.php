@php
    $edit_url = route('admin.store_users.edit', [$store, $user]);
    $delete_url = route('admin.store_users.destroy', [$store, $user]);
@endphp

<a href="{{ $edit_url }}" class="btn btn-warning">Edit</a>
<form action="{{ $delete_url }}" method="POST" style="display:inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
</form>
