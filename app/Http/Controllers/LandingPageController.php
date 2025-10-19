<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $featuredSuppliers = Supplier::where('is_featured', true)
            ->take(3)
            ->get();

        $featuredStores = Store::where('is_featured', true)
            ->take(3)
            ->get();

        return view('landing.index', compact('featuredSuppliers', 'featuredStores'));
    }
}
