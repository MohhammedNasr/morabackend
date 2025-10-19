<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Services\Representative\RepresentativeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RepresentativeController extends Controller
{
    protected $representativeService;

    public function __construct(RepresentativeService $representativeService)
    {
        $this->representativeService = $representativeService;
    }

    public function updateProfile(Request $request): JsonResponse
    {

        $representative = $this->getAuthenticatedRepresentative();
        if (!$representative) {
            return response()->json(['message' => 'Only representatives can access this endpoint'], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:representatives,email,'.$representative->id,
            'password' => 'sometimes|string|min:8'
        ]);

        return $this->representativeService->updateProfile(
            $representative,
            $request->only(['name', 'password'])
        );


    }

    private function getAuthenticatedRepresentative(): ?Representative
    {
        // Try to authenticate as a representative directly
        $representative = auth('representative')->user();

        // If not authenticated as a representative, check if authenticated as a user
        if (!$representative) {
            // Try to authenticate with sanctum
            $user = auth('sanctum')->user();

            if (!$user || !$user->role || $user->role->slug !== 'representative') {
                return null;
            }

            // Try to find the representative by user_id if there's a relationship
            $representative = Representative::where('user_id', $user->id)->first();
        }

        return $representative;
    }
}
