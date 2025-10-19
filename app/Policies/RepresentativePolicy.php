<?php

namespace App\Policies;

use App\Models\Representative;
use App\Models\Supplier;
use Illuminate\Auth\Access\HandlesAuthorization;

class RepresentativePolicy
{
    use HandlesAuthorization;

    public function update(Supplier $supplier, Representative $representative)
    {
        return $supplier->id === $representative->supplier_id;
    }
}
