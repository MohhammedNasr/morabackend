<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\StoreBranch;
use Illuminate\Http\Request;

class StoreBranchController extends Controller
{
    public function index(Request $request, Store $store)
    {
        $branches = $store->branches()->with(['city', 'area'])->paginate(10);
        return view('admin.store_branches.index', compact('store', 'branches'));
    }

    public function create(Store $store)
    {
        $cities = \App\Models\City::all();
        $areas = \App\Models\Area::all();
        return view('admin.store_branches.create', compact('store', 'cities', 'areas'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required|exists:areas,id',
            'street_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_main' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $storeBranch = new StoreBranch($request->all());
        $store->branches()->save($storeBranch);

        return redirect()->route('admin.stores.branches.index', $store)
            ->with('success', 'Branch created successfully.');
    }

    public function datatable(Request $request)
    {
        $storeId = $request->input('store');
        $search = $request->input('search.value');

        $query = StoreBranch::with(['city', 'area'])
            ->where('store_id', $storeId)
            ->select(['id', 'name', 'city_id', 'area_id', 'street_name', 'phone', 'balance_limit', 'is_active', 'created_at']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('street_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('city', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('area', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $dt = datatables()->eloquent($query)
            ->addColumn('address', function($branch) {
                $parts = [];
                if ($branch->city) $parts[] = $branch->city->name;
                if ($branch->area) $parts[] = $branch->area->name;
                if ($branch->street_name) $parts[] = $branch->street_name;
                return !empty($parts) ? implode(', ', $parts) : '-';
            })
            ->addColumn('status', function($branch) {
                return $branch->is_active;
            });
        
        // Skip actions column when called from React frontend (has 'store' parameter)
        // This avoids blade view rendering errors when store parameter is not available in route
        if (!$storeId) {
            $dt->addColumn('actions', function($branch) {
                return view('admin.store_branches.partials.actions', ['branch' => $branch])->render();
            })
            ->rawColumns(['actions', 'status']);
        }
        
        return $dt->make(true);
    }

    public function edit($store, StoreBranch $store_branch)
    {
        return view('admin.store_branches.edit', compact('store', 'store_branch'));
    }

    public function update(Request $request, Store $store, StoreBranch $store_branch)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'area_id' => 'required|exists:areas,id',
            'street_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_main' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $store_branch->update($request->all());

        return redirect()->route('admin.stores.branches.index', $store)
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Store $store, StoreBranch $store_branch)
    {
        $store_branch->delete();
        return redirect()->route('admin.stores.branches.index', $store)
            ->with('success', 'Branch deleted successfully.');
    }

    /**
     * Update balance limit for a store branch
     */
    public function updateBalanceLimit(Request $request, StoreBranch $branch)
    {
        $request->validate([
            'balance_limit' => 'required|numeric|min:0',
        ]);

        $branch->update([
            'balance_limit' => $request->balance_limit
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Balance limit updated successfully',
            'data' => [
                'balance_limit' => $branch->balance_limit
            ]
        ]);
    }

    /**
     * Toggle branch active status
     */
    public function toggleStatus(Request $request, StoreBranch $branch)
    {
        $branch->update([
            'is_active' => !$branch->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Branch status updated successfully',
            'data' => [
                'is_active' => $branch->is_active
            ]
        ]);
    }
}
