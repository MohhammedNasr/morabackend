<?php

namespace App\Services\Representative;

use App\Http\Resources\RepresentativeResource;
use App\Models\Representative;
use App\Services\BaseService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RepresentativeService extends BaseService
{

    public function updateProfile(Representative $representative, array $data): JsonResponse
    {
        $updates = [];
        if (isset($data['name'])) {
            $updates['name'] = $data['name'];
        }
        if (isset($data['password'])) {
            $updates['password'] = Hash::make($data['password']);
        }

        $representative->update($updates);

        return $this->successResponse(
            data: new RepresentativeResource($representative->fresh()),
            message: __('api.representative_updated')
        );
    }
}
