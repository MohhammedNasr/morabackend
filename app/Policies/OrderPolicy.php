<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\Representative;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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

        // Handle Supplier model directly
        if ($user instanceof \App\Models\Supplier) {
            return true;
        }

        // Handle User model
        if ($user instanceof User) {
            return $user->role && ($user->role->slug === 'store-owner' || $user->role->slug === 'supplier');
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

        // Handle Supplier model directly
        if ($user instanceof \App\Models\Supplier) {
            return $order->subOrders()->where('supplier_id', $user->id)->exists();
        }

        // Handle User model
        if ($user instanceof User) {
            // Store owners can view their own store's orders
            if ($user->role && $user->role->slug === 'store-owner') {
                $userStores = Store::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->pluck('id')->toArray();

                return in_array($order->store_id, $userStores);
            }

            // Suppliers can view orders that have sub-orders assigned to them
            if ($user->role && $user->role->slug === 'supplier' && $user->supplier) {
                return $order->subOrders()->where('supplier_id', $user->supplier->id)->exists();
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
            return true;
        }

        // Handle User model
        if ($user instanceof User) {
            return $user->role && $user->role->slug === 'store-owner';
        }

        // Handle User model
        if ($user instanceof Representative) {
            return $user->role && $user->role->slug === 'representative';
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
        // Handle Store model directly
        if ($user instanceof Store) {
            return $order->store_id === $user->id;
        }

        // Handle User model
        if ($user instanceof User) {
            // Store owners can update their own store's orders
            if ($user->role && $user->role->slug === 'store-owner') {
                $userStores = Store::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->pluck('id')->toArray();

                return in_array($order->store_id, $userStores);
            }
        }

        return false;
    }

    /**
     * Determine whether the user can modify the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function modify($user, Order $order)
    {
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can verify the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function verify($user, Order $order)
    {
        // Only the customer who placed the order can verify it
        if ($user instanceof User) {
            return $user->id === $order->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can cancel the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function cancel($user, Order $order)
    {
        // Handle Store model directly
        if ($user instanceof Store) {
            return $order->store_id === $user->id;
        }

        // Handle User model
        if ($user instanceof User) {
            // Store owners can cancel their own store's orders
            if ($user->role && $user->role->slug === 'store-owner') {
                $userStores = Store::whereHas('users', function ($query) use ($user) {
                    $query->where('users.id', $user->id);
                })->pluck('id')->toArray();

                return in_array($order->store_id, $userStores);
            }

            // Customers can cancel their own orders
            return $user->id === $order->user_id;
        }

        return false;
    }

    /**
     * Determine whether the user can approve the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function approve($user, Order $order)
    {
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can reject the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function reject($user, Order $order)
    {
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can deliver the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function deliver($user, Order $order)
    {
        return $this->update($user, $order);
    }

    /**
     * Determine whether the user can complete the order.
     *
     * @param  mixed  $user
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function complete($user, Order $order)
    {
        return $this->update($user, $order);
    }
}
