<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\UpdateStoreProfileRequest;
use App\Http\Resources\StoreResource;
use App\Http\Resources\UserResource;
use App\Services\Store\StoreProfileService;

class StoreProfileController extends Controller
{
    protected $storeProfileService;

    public function __construct(StoreProfileService $storeProfileService)
    {
        $this->storeProfileService = $storeProfileService;
    }

    public function update(UpdateStoreProfileRequest $request)
    {
        $user = $request->user();
        return $this->storeProfileService->updateProfile($user, $request->validated());

        // return response()->json([
        //     'message' => __('Store profile updated successfully'),
        //     'data' => [
        //         'user' => new UserResource($result['user']),
        //         'store' => new StoreResource($result['store']->load('branches')),
        //     ]
        // ]);
    }
}
