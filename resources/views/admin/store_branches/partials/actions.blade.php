@if($branch)
    <a href="{{ route('admin.stores.branches.edit', ['store' => $branch->store_id, 'store_branch' => $branch->id]) }}"
       class="btn btn-sm btn-clean btn-icon"
       title="Edit">
        <i class="la la-edit"></i>
    </a>

    <form action="{{ route('admin.stores.branches.destroy', ['store' => $branch->store_id, 'store_branch' => $branch->id]) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this branch?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-sm btn-clean btn-icon"
                title="Delete">
            <i class="la la-trash"></i>
        </button>
    </form>
@endif
