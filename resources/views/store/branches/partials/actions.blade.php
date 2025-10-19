@if(isset($edit_url))
<a href="{{ $edit_url }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
    <i class="la la-edit"></i>
</a>
@endif

@if(isset($delete_url))
<form action="{{ $delete_url }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
        <i class="la la-trash"></i>
    </button>
</form>
@endif
