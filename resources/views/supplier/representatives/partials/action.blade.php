<div class="btn-group">
    <a href="{{ route('supplier.representatives.edit', $representative) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
        <i class="la la-edit"></i>
    </a>
    <form action="{{ route('supplier.representatives.destroy', $representative) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm('Are you sure?')">
            <i class="la la-trash"></i>
        </button>
    </form>
</div>
