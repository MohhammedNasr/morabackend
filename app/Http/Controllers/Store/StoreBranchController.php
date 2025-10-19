<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoreBranch;
use Illuminate\Http\Request;

class StoreBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
        $this->middleware(function ($request, $next) {
            $user = auth()->guard('web')->user();
            if (!$user || !$user->store) {
                abort(403, 'Unauthorized action.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        // Return JSON for API requests (Flutter app)
        if ($request->wantsJson() || $request->header('Accept') === 'application/json') {
            $branches = StoreBranch::with(['city', 'area'])
                ->where('store_id', auth()->guard('web')->user()->store->id)
                ->get()
                ->map(function($branch) {
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name,
                        'phone' => $branch->phone,
                        'street_name' => $branch->street_name,
                        'building_number' => $branch->building_number,
                        'floor_number' => $branch->floor_number,
                        'balance_limit' => (float) $branch->balance_limit,
                        'is_main' => $branch->is_main,
                        'is_active' => $branch->is_active,
                        'notes' => $branch->notes,
                        'city' => $branch->city ? [
                            'id' => $branch->city->id,
                            'name' => $branch->city->name,
                        ] : null,
                        'area' => $branch->area ? [
                            'id' => $branch->area->id,
                            'name' => $branch->area->name,
                        ] : null,
                        'created_at' => $branch->created_at?->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json($branches);
        }

        return view('store.branches.index');
    }

    public function create()
    {
        return view('store.branches.create');
    }

    public function store(Request $request)
    {
        // Validation and store logic
    }

    public function show(StoreBranch $storeBranch)
    {
        return view('store.branches.show', compact('storeBranch'));
    }

    public function edit(StoreBranch $storeBranch)
    {
        $cities = \App\Models\City::all();
        $areas = \App\Models\Area::where('city_id', $storeBranch->city_id)->get();
        return view('store.branches.edit', compact('storeBranch', 'cities', 'areas'));
    }

    public function update(Request $request, StoreBranch $storeBranch)
    {
        // Validation and update logic
    }

    public function destroy(StoreBranch $storeBranch)
    {
        $storeBranch->delete();
        return redirect()->route('store.branches.index');
    }

    public function datatable(Request $request)
    {
        $branches = StoreBranch::query()
            ->where('store_id', auth()->guard('web')->user()->store->id)
            ->get();

        return datatables()->of($branches)
            ->addColumn('actions', function ($branch) {
                return [
                    'edit_url' => route('store.branches.edit', $branch),
                    'delete_url' => route('store.branches.destroy', $branch)
                ];
            })
            ->editColumn('edit_url', function($branch) {
                return route('store.branches.edit', $branch);
            })
            ->editColumn('delete_url', function($branch) {
                return route('store.branches.destroy', $branch);
            })
            ->make(true);
    }
}
