<?php

namespace App\Services\Branch;

use App\Http\Resources\StoreBranchResource;
use App\Services\BaseService;
use App\Models\StoreBranch;
use Illuminate\Http\JsonResponse;

class BranchService extends BaseService
{
    /**
     * Get all branches for authenticated user's store
     */
    public function getBranches(int $storeId): JsonResponse
    {
        try {
            $branches = StoreBranch::with(['city', 'area'])
                ->where('store_id', $storeId)
                ->get();

            return $this->successResponse(
                data: StoreBranchResource::collection($branches),
                message: __('api.branches_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create new branch for authenticated user's store
     */
    public function createBranch(int $storeId, array $data): JsonResponse
    {
        try {
            $data['store_id'] = $storeId;
            $branch = StoreBranch::create($data);

            return $this->successResponse(
                data: StoreBranchResource::make($branch),
                message: __('api.branch_created')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update branch for authenticated user's store
     */
    public function updateBranch(int $storeId, int $branchId, array $data): JsonResponse
    {
        try {
            $branch = StoreBranch::where('store_id', $storeId)
                ->findOrFail($branchId);

            $branch->update($data);

            return $this->successResponse(
                data: StoreBranchResource::make($branch),
                message: __('api.branch_updated')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get a single branch
     */
    public function getBranch(int $storeId, int $branchId): JsonResponse
    {
        try {
            $branch = StoreBranch::with(['city', 'area'])
                ->where('store_id', $storeId)
                ->findOrFail($branchId);

            return $this->successResponse(
                data: StoreBranchResource::make($branch),
                message: __('api.branch_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Delete branch for authenticated user's store
     */
    public function deleteBranch(int $storeId, int $branchId): JsonResponse
    {
        try {
            $branch = StoreBranch::where('store_id', $storeId)
                ->findOrFail($branchId);

            $branch->delete();

            return $this->successResponse(
                message: __('api.branch_deleted')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
