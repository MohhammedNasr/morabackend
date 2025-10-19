<?php

namespace App\Services\Supplier;

use App\Http\Resources\SupplierResource;
use App\Http\Resources\UserResource;
use App\Models\Supplier;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;

class SupplierProfileService extends BaseService
{
    public function updateProfile(Supplier $supplier, array $data): JsonResponse
    {
        $supplier->update($data);
        return $this->successResponse(
            data: [
                'user' => new UserResource($supplier),
                'supplier' => new SupplierResource($supplier)
            ],
            message: 'Supplier profile updated successfully',
        );
    }
}
