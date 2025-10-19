<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('areas')->paginate(10);
        return view('admin.cities.index', compact('cities'));
    }

    public function show(City $city)
    {
        $city->load('areas');
        return view('admin.cities.show', compact('city'));
    }

    public function create()
    {
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cities'
        ]);

        City::create($validated);
        return redirect()->route('admin.cities.index')->with('success', 'City created successfully');
    }

    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:cities,code,'.$city->id
        ]);

        $city->update($validated);
        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully');
    }

    public function datatable(Request $request)
    {
        $cities = City::withCount('areas')
            ->when($request->search, function($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('name_en', 'like', '%'.$request->search.'%')
                      ->orWhere('name_ar', 'like', '%'.$request->search.'%');
                });
            });

        return DataTables::of($cities)
            ->addColumn('areas_count', function($city) {
                return $city->areas_count;
            })
            ->addColumn('actions', function($city) {
                return view('admin.cities.partials.actions', compact('city'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
