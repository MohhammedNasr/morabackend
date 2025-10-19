<?php

namespace App\Services\Store;

use App\Http\Resources\StoreResource;
use App\Http\Resources\StoreBranchResource;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Services\BaseService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class StoreService extends BaseService
{
    use ApiResponse;

    public function getStoresWithFilters(?int $cityId = null, ?int $areaId = null, ?string $search = null): JsonResponse
    {
        $query = Store::query()
            ->with(['branches.city', 'branches.area'])
            ->active();

        if ($cityId) {
            $query->whereHas('branches', function($q) use ($cityId) {
                $q->where('city_id', $cityId);
            });
        }

        if ($areaId) {
            $query->whereHas('branches', function($q) use ($areaId) {
                $q->where('area_id', $areaId);
            });
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $stores = $query->paginate(15);

        return $this->successResponse(
            data: StoreResource::collection($stores),
            message: __('api.stores_retrieved')
        );
    }

    public function getStoreBranches(int $storeId): JsonResponse
    {
        $branches = StoreBranch::where('store_id', $storeId)
            ->with(['city', 'area'])
            ->active()
            ->get();

        return $this->successResponse(
            data: StoreBranchResource::collection($branches),
            message: __('api.store_branches_retrieved')
        );
    }
}
