<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\RegisterStoreRequest;
use App\Services\Store\StoreRegistrationService;
use Illuminate\Http\JsonResponse;

class StoreRegistrationController extends Controller
{
    protected StoreRegistrationService $storeRegistrationService;

    public function __construct(StoreRegistrationService $storeRegistrationService)
    {
        $this->storeRegistrationService = $storeRegistrationService;
    }

    public function register(RegisterStoreRequest $request): JsonResponse
    {
        return $this->storeRegistrationService->register($request->all());
    }
}
