<a href="{{ route('admin.cities.show', $city) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
    <i class="la la-eye"></i>
</a>
<a href="{{ route('admin.areas.index', ['city_id' => $city->id]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View Areas">
    <i class="la la-list"></i>
</a>
<a href="{{ route('admin.cities.edit', $city) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
    <i class="la la-edit"></i>
</a>
<form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm('Are you sure you want to delete this city?')">
        <i class="la la-trash"></i>
    </button>
</form>
