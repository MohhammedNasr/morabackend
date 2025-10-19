
    <a href="{{ route('admin.stores.show', $store) }}"
       class="btn btn-sm btn-clean btn-icon"
       title="View">
        <i class="la la-eye"></i>
    </a>
{{--
       title="View Branches">
        <i class="la la-code-branch"></i>
    </a> --}}

    <a href="{{ route('admin.orders.index', ['store_id' => $store->id]) }}"
       class="btn btn-sm btn-clean btn-icon"
       title="View Orders">
        <i class="la la-shopping-cart"></i>
    </a>
{{--
    <a href="{{ route('admin.store_users.index', ['store' => $store->id]) }}"
       class="btn btn-sm btn-clean btn-icon"
       title="View Users">
        <i class="la la-users"></i>
    </a> --}}



    <a href="{{ route('admin.stores.edit', $store) }}"
       class="btn btn-sm btn-clean btn-icon"
       title="Edit">
        <i class="la la-edit"></i>
    </a>


    <form action="{{ route('admin.stores.destroy', $store) }}"
          method="POST"
          class="d-inline"
          onsubmit="return confirm('Are you sure you want to delete this store?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="btn btn-sm btn-clean btn-icon"
                title="Delete">
            <i class="la la-trash"></i>
        </button>
    </form>
