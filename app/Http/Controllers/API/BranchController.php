<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Services\Branch\BranchService;
use Illuminate\Http\Request;
use App\Http\Requests\Store\StoreBranchRequest;

class BranchController extends Controller
{
    protected $branchService;
    protected $store;
    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Get all branches for authenticated user's store
     */
    public function index(Request $request)
    {

        $store = Store::where('owner_id', $request->user()->id)->first();
        $storeId = $store->id;
        return $this->branchService->getBranches($storeId);
    }

    /**
     * Create new branch for authenticated user's store
     */
    public function store(StoreBranchRequest $request)
    {
        $store = Store::where('owner_id', $request->user()->id)->first();
        $storeId = $store->id;
        return $this->branchService->createBranch($storeId, $request->all());
    }

    /**
     * Update branch for authenticated user's store
     */
    public function update(StoreBranchRequest $request, $id)
    {
        $store = Store::where('owner_id', $request->user()->id)->first();
        $storeId = $store->id;
        return $this->branchService->updateBranch($storeId, $id, $request->all());
    }

    /**
     * Get a single branch
     */
    public function show(Request $request, $id)
    {
        $store = Store::where('owner_id', $request->user()->id)->first();
        $storeId = $store->id;
        return $this->branchService->getBranch($storeId, $id);
    }

    /**
     * Delete branch for authenticated user's store
     */
    public function destroy(Request $request, $id)
    {
        $store = Store::where('owner_id', $request->user()->id)->first();
        $storeId = $store->id;
        return $this->branchService->deleteBranch($storeId, $id);
    }
}
