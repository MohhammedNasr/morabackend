<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Store;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StoreOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Allow admins to do anything.
     *
     * @param mixed $user
     * @return bool|null
     */
    public function before($user)
    {
        // Skip the check if the user is not a User model
        if (!($user instanceof User)) {
            return null;
        }

        if ($user->role && $user->role->slug === 'admin') {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  mixed  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny($user)
    {
        // Handle Store model directly
        if ($user instanceof Store) {
            return true;
        }

        // Handle User model
        if ($user instanceof User) {
            return $user->role && $user->role->slug === 'store-owner';
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view($user, Order $order)
    {
        // Handle Store model directly
        if ($user instanceof Store) {
            return $order->store_id === $user->id;
        }

        // Handle User model
        if ($user instanceof User) {
            if ($user->role && $user->role->slug === 'store-owner') {
                $userStores = Store::whereHas('users', function($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->pluck('id')->toArray();

                return in_array($order->store_id, $userStores);
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  mixed  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create($user)
    {
        // Handle Store model directly
        if ($user instanceof Store) {
            return $user->is_verified;
        }

        // Handle User model
        if ($user instanceof User) {
            if ($user->role && $user->role->slug === 'store-owner') {
                $store = $user->primaryStores()->first();
                return $store && $store->is_verified;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, Order $order)
    {
        return $this->view($user, $order);
    }
}
