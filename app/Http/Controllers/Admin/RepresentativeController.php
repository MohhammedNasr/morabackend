<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RepresentativeController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::all();
        return view('admin.representatives.create', compact('suppliers'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $representatives = Representative::with('supplier')->select('*');

            return DataTables::of($representatives)
                ->addColumn('action', function($representative) {
                    return view('admin.representatives.actions', compact('representative'))->render();
                })
                ->editColumn('created_at', function($representative) {
                    return $representative->created_at->format('Y-m-d H:i');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $suppliers = Supplier::all();
        return view('admin.representatives.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:representatives',
            'email' => 'nullable|email|unique:representatives',
            'password' => 'required|string|min:8',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $representative = Representative::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'supplier_id' => $request->supplier_id,
            'role_id' => 2
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Representative created successfully'
        ]);
    }

    public function edit(Representative $representative)
    {
        $suppliers = Supplier::all();
        return response()->json([
            'representative' => $representative,
            'suppliers' => $suppliers
        ]);
    }

    public function update(Request $request, Representative $representative)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:representatives,phone,'.$representative->id,
            'email' => 'nullable|email|unique:representatives,email,'.$representative->id,
            'password' => 'nullable|string|min:8',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'supplier_id' => $request->supplier_id
        ];

        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $representative->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Representative updated successfully'
        ]);
    }

    public function destroy(Representative $representative)
    {
        $representative->delete();
        return response()->json([
            'success' => true,
            'message' => 'Representative deleted successfully'
        ]);
    }

    public function datatable(Request $request)
    {
        $representatives = Representative::query()
            ->with(['supplier', 'role']);

        // Apply filters
        if ($request->has('supplier_id') && $request->supplier_id) {
            $representatives->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $isActive = $request->status === 'active' ? 1 : 0;
            $representatives->where('is_active', $isActive);
        }

        if ($request->has('date_range') && $request->date_range) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $representatives->whereBetween('created_at', [
                    \Carbon\Carbon::parse($dates[0])->startOfDay(),
                    \Carbon\Carbon::parse($dates[1])->endOfDay()
                ]);
            }
        }

        return DataTables::of($representatives)
            ->addColumn('supplier_name', function ($representative) {
                return $representative->supplier ? $representative->supplier->name : 'No Supplier';
            })
            ->editColumn('created_at', function ($representative) {
                return $representative->created_at->format('Y-m-d H:i');
            })
            ->make(true);
    }
}
