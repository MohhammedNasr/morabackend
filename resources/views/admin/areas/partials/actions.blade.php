@php
    $editUrl = route('admin.areas.edit', $area);
    $deleteUrl = route('admin.areas.destroy', $area);
    $csrfToken = csrf_token();
@endphp

<div class="d-flex">
    <a href="{{ $editUrl }}" class="btn btn-sm btn-primary me-2">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ $deleteUrl }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
