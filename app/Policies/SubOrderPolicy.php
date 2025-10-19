<?php

namespace App\Policies;

use App\Models\SubOrder;
use App\Models\User;
use App\Models\Representative;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubOrderPolicy
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
        // Handle Supplier model directly
        if ($user instanceof \App\Models\Supplier) {
            return true;
        }

        // Handle Representative model directly
        if ($user instanceof Representative) {
            return true;
        }

        // Handle User model
        if ($user instanceof User) {
            return $user->role && ($user->role->slug === 'supplier' || $user->role->slug === 'representative');
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  mixed  $user
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view($user, SubOrder $subOrder)
    {
        // Handle Supplier model directly
        if ($user instanceof \App\Models\Supplier) {
            return $subOrder->supplier_id === $user->id;
        }

        // Handle Representative model directly
        if ($user instanceof Representative) {
            return $subOrder->representative_id === $user->id;
        }

        // Handle User model
        if ($user instanceof User) {
            // Supplier can view sub-orders that belong to them
            if ($user->role && $user->role->slug === 'supplier' && $user->supplier) {
                return $subOrder->supplier_id === $user->supplier->id;
            }

            // Representative can view sub-orders assigned to them
            if ($user->role && $user->role->slug === 'representative') {
                $representative = Representative::where('user_id', $user->id)->first();
                if ($representative) {
                    return $subOrder->representative_id === $representative->id;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  mixed  $user
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update($user, SubOrder $subOrder)
    {
        return $this->view($user, $subOrder);
    }

    /**
     * Determine whether the user can change the status of the model.
     *
     * @param  mixed  $user
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function changeStatus($user, SubOrder $subOrder)
    {
        return $this->update($user, $subOrder);
    }

    /**
     * Determine whether the user can modify the sub-order.
     *
     * @param  mixed  $user
     * @param  \App\Models\SubOrder  $subOrder
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function modifySubOrder($user, SubOrder $subOrder)
    {
        // return true;
        // Allow admin to modify any sub-order
        if ($user instanceof User && $user->role && $user->role->slug === 'admin') {
            return true;
        }

        // Handle Supplier model directly
        if ($user instanceof \App\Models\Supplier) {
            return $subOrder->supplier_id === $user->id &&
                   in_array($subOrder->status, SubOrder::$statusValues);
        }

        // Handle User model with supplier role
        if ($user instanceof User && $user->role && $user->role->slug === 'supplier' && $user->supplier) {
            return $subOrder->supplier_id === $user->supplier->id &&
                   in_array($subOrder->status, SubOrder::$statusValues);
        }

        // Handle Representative model directly
        if ($user instanceof Representative) {
            return $subOrder->representative_id === $user->id &&
                   in_array($subOrder->status, ['acceptedByRep', 'outForDelivery']);
        }

        // Handle User model with representative role
        if ($user instanceof User && $user->role && $user->role->slug === 'representative') {
            $representative = Representative::where('user_id', $user->id)->first();
            if ($representative) {
                return $subOrder->representative_id === $representative->id &&
                       in_array($subOrder->status, ['acceptedByRep', 'outForDelivery']);
            }
        }

        return false;
    }
}
