<?php

namespace App\Services\City;

use App\Models\City;
use App\Http\Resources\CityResource;
use App\Traits\ApiResponse;

class CityService
{
    use ApiResponse;

    public function getCities()
    {
        $cities = City::with('areas')->get();

        return $this->successResponse(
            CityResource::collection($cities),
            __('api.cities_retrieved')
        );
    }

    public function getCity($id)
    {
        $city = City::with('areas')->findOrFail($id);

        return $this->successResponse(
            new CityResource($city),
            __('api.city_retrieved')
        );
    }
}
