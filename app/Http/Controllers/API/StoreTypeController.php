<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Store\StoreTypeService;
use Illuminate\Http\JsonResponse;

class StoreTypeController extends Controller
{
    protected StoreTypeService $storeTypeService;

    public function __construct(StoreTypeService $storeTypeService)
    {
        $this->storeTypeService = $storeTypeService;
    }

    public function index(): JsonResponse
    {
        return $this->storeTypeService->getStoreTypes();
    }
}
