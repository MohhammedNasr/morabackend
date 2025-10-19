<a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
    <i class="la la-eye"></i>
</a>



@if ($order->status === 'pending')
    {{-- <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
        <i class="la la-edit"></i>
    </a> --}}

    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline"
        onsubmit="return confirm('Are you sure you want to delete this order?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
            <i class="la la-trash"></i>
        </button>
    </form>
@endif