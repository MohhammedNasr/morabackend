<div class="d-flex justify-content-center">
    <a href="{{ route('admin.wallets.edit', $wallet->id) }}" class="btn btn-icon btn-light-primary btn-sm me-2" title="Edit">
        <i class="fas fa-edit"></i>
    </a>

    <form action="{{ route('admin.wallets.destroy', $wallet->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-light-danger btn-sm me-2" title="Delete" onclick="return confirm('Are you sure?')">
            <i class="fas fa-trash"></i>
        </button>
    </form>

    <button type="button" class="btn btn-icon btn-light-{{ $wallet->status === 'active' ? 'success' : 'danger' }} btn-sm toggle-status-btn" 
        data-url="{{ route('admin.wallets.toggle-status', $wallet->id) }}"
        title="{{ $wallet->status === 'active' ? 'Deactivate' : 'Activate' }}">
        <i class="fas fa-power-off"></i>
    </button>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.toggle-status-btn').click(function() {
        const button = $(this);
        const url = button.data('url');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.status === 'success') {
                    button.toggleClass('btn-light-success btn-light-danger');
                    button.attr('title', response.new_status === 'active' ? 'Deactivate' : 'Activate');
                    toastr.success(response.message);
                }
            }
        });
    });
});
</script>
@endpush
