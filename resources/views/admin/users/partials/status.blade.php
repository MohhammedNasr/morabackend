@if($user->status === 'active')
    <span class="badge badge-light-success">Active</span>
@else
    <span class="badge badge-light-danger">Inactive</span>
@endif
