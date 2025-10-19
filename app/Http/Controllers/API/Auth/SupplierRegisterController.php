<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Models\Supplier;
use App\Services\Supplier\SupplierService;
use Illuminate\Http\JsonResponse;

class SupplierRegisterController extends Controller
{
    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function register(CreateSupplierRequest $request): JsonResponse
    {
        return $this->supplierService->createSupplier($request->all());
    }
}
