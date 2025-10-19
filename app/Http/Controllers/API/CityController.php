<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\City\CityService;
use App\Traits\ApiResponse;

class CityController extends Controller
{
    use ApiResponse;

    protected $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    public function index()
    {
        return $this->cityService->getCities();
    }

    public function show($id)
    {
        return $this->cityService->getCity($id);
    }
}
