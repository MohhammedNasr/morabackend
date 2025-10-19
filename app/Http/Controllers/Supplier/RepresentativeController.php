<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('supplier.representatives.index');
    }

    public function datatable(Request $request)
    {
        try {
            /** @var Supplier $supplier */
            $supplier = Auth::user();
            Log::info('Datatable request received from supplier ID: ' . $supplier->id);
            Log::info('Request data:', $request->all());

            $query = Representative::where('supplier_id', $supplier->id);
            $count = $query->count();
            Log::info("Found {$count} representatives for supplier {$supplier->id}");

            if ($count === 0) {
                Log::warning('No representatives found for supplier', ['supplier_id' => $supplier->id]);
            }

            return DataTables::eloquent($query)
                ->addColumn('name', function ($representative) {
                    return $representative->name;
                })
                ->addColumn('email', function ($representative) {
                    return $representative->email;
                })
                ->addColumn('phone', function ($representative) {
                    return $representative->phone;
                })
                ->addColumn('status', function ($representative) {
                    $status = $representative->is_active ? 'Active' : 'Inactive';
                    $color = $representative->is_active ? 'success' : 'danger';
                    return '<span class="kt-badge kt-badge--' . $color . ' kt-badge--inline">' . $status . '</span>';
                })
                ->addColumn('actions', function ($representative) {
                    return view('supplier.representatives.partials.action', compact('representative'));
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('search.value')) {
                        $search = $request->input('search.value');
                        $query->where(function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                    }
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('Datatable error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.representatives.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:8'
        ]);

        $supplier = Auth::user();
        $supplier->representatives->create($request->all());

        return redirect()->route('supplier.representatives.index')
            ->with('success', 'Representative created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Representative $representative)
    {
        //  $this->authorize('view', $representative);
        return view('supplier.representatives.show', compact('representative'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Representative $representative)
    {
        // $this->authorize('update', $representative);
        return view('supplier.representatives.edit', compact('representative'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Representative $representative)
    {
        // $this->authorize('update', $representative);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $representative->id,
            'phone' => 'required|unique:users,phone,' . $representative->id,
            'password' => 'nullable|min:8'
        ]);

        $representative->update($request->all());
        return redirect()->route('supplier.representatives.index')
            ->with('success', 'Representative updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function toggleStatus(Request $request, Representative $representative)
    {
        //dd($representative);
        $this->authorize('update', $representative);

        try {

            // $validated = $request->validate([
            //     'is_active' => 'required|boolean'
            // ]);

            $isActive = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
            $representative->update(['is_active' => $isActive]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Toggle status error: ' . $e->getMessage());
            return response()->json([
                'error' => true,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    public function destroy(Representative $representative)
    {
        //  $this->authorize('delete', $representative);
        $representative->delete();
        return redirect()->route('supplier.representatives.index')
            ->with('success', 'Representative deleted successfully');
    }
}
