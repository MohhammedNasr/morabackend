<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\UpdateSupplierProfileRequest;
use App\Services\Supplier\SupplierProfileService;

class SupplierProfileController extends Controller
{
    protected $profileService;

    public function __construct(SupplierProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function update(UpdateSupplierProfileRequest $request)
    { 
        return $this->profileService->updateProfile($request->user(), $request->validated());
    }
}
