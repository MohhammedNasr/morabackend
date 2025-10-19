
    <a href="{{ route('admin.suppliers.show', $supplier) }}" 
       class="btn btn-sm btn-clean btn-icon" 
       title="View">
        <i class="la la-eye"></i>
    </a>



    <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
       class="btn btn-sm btn-clean btn-icon" 
       title="Edit">
        <i class="la la-edit"></i>
    </a>



    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" 
          method="POST" 
          class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this supplier?')">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="btn btn-sm btn-clean btn-icon" 
                title="Delete">
            <i class="la la-trash"></i>
        </button>
    </form>

