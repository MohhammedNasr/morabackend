<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AreaController extends Controller
{
    public function index()
    {
        $cities = City::all();
        return view('admin.areas.index', compact('cities'));
    }

    public function create()
    {
        $cities = City::all();
        return view('admin.areas.create', compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:areas'
        ]);

        Area::create($validated);
        return redirect()->route('admin.areas.index')->with('success', 'Area created successfully');
    }

    public function edit(Area $area)
    {
        $cities = City::all();
        return view('admin.areas.edit', compact('area', 'cities'));
    }

    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:areas,code,'.$area->id
        ]);

        $area->update($validated);
        return redirect()->route('admin.areas.index')->with('success', 'Area updated successfully');
    }

    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('admin.areas.index')->with('success', 'Area deleted successfully');
    }

    public function show(City $city)
    {
        $areas = $city->areas()->paginate(10);
        return view('admin.areas.show', compact('city', 'areas'));
    }

    public function datatable(Request $request)
    {
        try {
            $areas = Area::with('city')
                ->select('areas.*')
                ->withTrashed();

            // Apply search filter
            // if ($request->has('search') && $request->search['value']) {
            //     $search = $request->search['value'];
            //     $areas->where(function($query) use ($search) {
            //         $query->where('name_en', 'like', "%$search%")
            //             ->orWhere('name_ar', 'like', "%$search%")
            //             ->orWhere('code', 'like', "%$search%")
            //             ->orWhereHas('city', function($q) use ($search) {
            //                 $q->where('name_en', 'like', "%$search%")
            //                   ->orWhere('name_ar', 'like', "%$search%");
            //             });
            //     });
            // }

            // Apply city filter
            if ($request->has('city_id') && $request->city_id) {
                $areas->where('city_id', $request->city_id);
            }

            // Apply status filter
            if ($request->has('status') && $request->status) {
                if ($request->status === 'active') {
                    $areas->whereNull('deleted_at');
                } elseif ($request->status === 'deleted') {
                    $areas->whereNotNull('deleted_at');
                }
            }

            return datatables()->eloquent($areas)
                ->addColumn('status', function($area) {
                    return $area->trashed() ? 'Deleted' : 'Active';
                })
                ->addColumn('actions', function($area) {
                    return view('admin.areas.partials.actions', compact('area'))->render();
                })
                ->editColumn('city.name_en', function($area) {
                    return $area->city->name_en ?? '';
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        } catch (\Exception $e) {
            \Log::error('Area Datatable Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'error' => 'An error occurred while processing your request',
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }
}
