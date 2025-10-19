<?php

namespace App\Models\Traits;

trait HasRoles
{
    /**
     * Check if user has the given role.
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->slug ? $this->role->slug === $role : false;
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is supplier.
     *
     * @return bool
     */
    public function isSupplier(): bool
    {
        return $this->hasRole('supplier');
    }

    /**
     * Check if user is store owner.
     *
     * @return bool
     */
    public function isStoreOwner(): bool
    {
        return $this->hasRole('store-owner');
    }
}
