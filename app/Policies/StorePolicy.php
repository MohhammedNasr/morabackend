<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Store $store): bool
    {
        return $user->isAdmin() || $store->users()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isStoreOwner();
    }

    public function update(User $user, Store $store): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Only primary owner can update store details
        return $store->owner()->where('user_id', $user->id)->exists();
    }

    public function verify(User $user, Store $store): bool
    {
        // Only primary owner can verify store
        return $store->owner()->where('user_id', $user->id)->exists() && !$store->is_verified;
    }
}
