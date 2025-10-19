@if($transaction->status === 'pending')
    <div class="dropdown">
        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
            {{ __('Actions') }}
        </button>
        <div class="dropdown-menu">
            @can('approve', $transaction)
                <a href="#" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('approve-form-{{ $transaction->id }}').submit();">
                    {{ __('Approve') }}
                </a>
                <form id="approve-form-{{ $transaction->id }}" 
                      action="{{ route('admin.wallet_transactions.approve', $transaction) }}" 
                      method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                </form>
            @endcan

            @can('reject', $transaction)
                <a href="#" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('reject-form-{{ $transaction->id }}').submit();">
                    {{ __('Reject') }}
                </a>
                <form id="reject-form-{{ $transaction->id }}" 
                      action="{{ route('admin.wallet_transactions.reject', $transaction) }}" 
                      method="POST" style="display: none;">
                    @csrf
                    @method('PUT')
                </form>
            @endcan
        </div>
    </div>
@else
    <span class="badge badge-{{ $transaction->status === 'approved' ? 'success' : 'danger' }}">
        {{ ucfirst($transaction->status) }}
    </span>
@endif
