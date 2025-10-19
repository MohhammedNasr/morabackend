
<a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-clean btn-icon" title="Edit">
    <i class="la la-edit"></i>
</a>


<button class="btn btn-sm btn-clean btn-icon toggle-status-btn" 
        title="Toggle Status"
        data-url="{{ route('admin.promotions.toggle-status', $promotion->id) }}"
        data-status="{{ $promotion->status }}">
    <i class="la {{ $promotion->status === 'active' ? 'la-toggle-on' : 'la-toggle-off' }}"></i>
</button>



<form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-clean btn-icon" title="Delete">
        <i class="la la-trash"></i>
    </button>
</form>

