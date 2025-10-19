<?php

namespace App\Policies;

use App\Models\SubOrder;
use App\Models\Supplier;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny($user)
    {
        return $user instanceof Supplier;
    }

    public function view(Supplier $supplier, SubOrder $subOrder)
    {
        return $supplier->id === $subOrder->supplier_id;
    }

    public function approve(Supplier $supplier, SubOrder $subOrder)
    {
        return $supplier->id === $subOrder->supplier_id;
    }

    public function reject(Supplier $supplier, SubOrder $subOrder)
    {
        return $supplier->id === $subOrder->supplier_id;
    }

    public function deliver(Supplier $supplier, SubOrder $subOrder)
    {
        return $supplier->id === $subOrder->supplier_id;
    }
}
