<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\SubOrder;
use App\Services\Home\HomeService;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    protected $homeService;

    public function __construct(HomeService $homeService)
    {
        $this->homeService = $homeService;
    }

    public function index(): JsonResponse
    {
        return $this->homeService->getHomeData();
    }

    public function representativeHome(): JsonResponse
    {
        $this->authorize('viewAny', SubOrder::class);

        $representative = $this->getAuthenticatedRepresentative();
        if (!$representative) {
            return response()->json(['message' => 'Only representatives can access this endpoint'], 403);
        }

        return $this->homeService->getRepresentativeHomeData($representative->id);
    }

    /**
     * Get the authenticated representative.
     *
     * @return Representative|null Returns the representative if found, or null if not
     */
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

public function supplierStatistics(): JsonResponse
{
    return $this->homeService->getSupplierStatistics();
}
}
