<a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
    <i class="la la-eye"></i>
</a>
<a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
    <i class="la la-edit"></i>
</a>
<form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm('Are you sure you want to delete this category?')">
        <i class="la la-trash"></i>
    </button>
</form>
