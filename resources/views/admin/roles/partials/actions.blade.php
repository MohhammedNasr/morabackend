
    <a href="{{ route('admin.roles.show', $role->id) }}" 
       class="btn btn-sm btn-clean btn-icon btn-icon-md" 
       title="View">
        <i class="la la-eye"></i>
    </a>


    <a href="{{ route('admin.roles.edit', $role->id) }}" 
       class="btn btn-sm btn-clean btn-icon btn-icon-md" 
       title="Edit">
        <i class="la la-edit"></i>
    </a>



    <form action="{{ route('admin.roles.destroy', $role->id) }}" 
          method="POST" 
          style="display: inline-block;"
          onsubmit="return confirm('Are you sure you want to delete this role?');">
        @csrf
        @method('DELETE')
        <button type="submit" 
                class="btn btn-sm btn-clean btn-icon btn-icon-md" 
                title="Delete">
            <i class="la la-trash"></i>
        </button>
    </form>

