<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Store\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $storeService;

    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }

    public function index(Request $request)
    {
        return $this->storeService->getStoresWithFilters(
            $request->input('city_id') ? (int)$request->input('city_id') : null,
            $request->input('area_id') ? (int)$request->input('area_id') : null,
            $request->input('search')
        );
    }

    public function branches($storeId)
    {
        return $this->storeService->getStoreBranches($storeId);
    }
}
