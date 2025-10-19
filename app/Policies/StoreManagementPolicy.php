<?php

namespace App\Policies;

use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreManagementPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can manage the store (add/remove owners, etc.).
     */
    public function manage(User $user, Store $store): bool
    {
        // Check if user is a primary owner of the store
        return $store->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('is_primary', true)
            ->exists();
    }

    /**
     * Determine if the user can view the store.
     */
    public function view(User $user, Store $store): bool
    {
        // User can view if they are any type of owner (primary or not)
        return $store->users()
            ->wherePivot('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine if the user can update the store.
     */
    public function update(User $user, Store $store): bool
    {
        // Only primary owner can update store details
        return $store->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('is_primary', true)
            ->exists();
    }

    /**
     * Determine if the user can delete the store.
     */
    public function delete(User $user, Store $store): bool
    {
        // Only primary owner can delete the store
        return $store->users()
            ->wherePivot('user_id', $user->id)
            ->wherePivot('is_primary', true)
            ->exists();
    }
}
