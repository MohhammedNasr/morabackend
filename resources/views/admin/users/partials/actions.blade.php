<div class="d-flex justify-content-end">
    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
        <i class="fas fa-edit"></i>
    </a>
    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
