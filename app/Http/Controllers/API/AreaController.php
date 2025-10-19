<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;

class AreaController extends Controller
{
    public function index()
    {
        return Area::all();
    }

    public function getByCity(City $city)
    {
        return $city->areas;
    }
}
