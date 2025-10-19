<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Promotion;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.promotions.index');
    }

    public function datatable(Request $request)
    {
        $promotions = Promotion::query();
        
        return DataTables::of($promotions)
            ->addColumn('actions', function($promotion) {
                return view('admin.promotions.partials.actions', compact('promotion'))->render();
            })
            ->editColumn('created_at', function($promotion) {
                return $promotion->created_at?->format('Y-m-d H:i') ?? '';
            })
            ->editColumn('discount', function($promotion) {
                return $promotion->discount_type === 'percentage' 
                    ? $promotion->discount_value . '%'
                    : number_format($promotion->discount_value, 2);
            })
            ->editColumn('minimum_order_amount', function($promotion) {
                return number_format($promotion->minimum_order_amount, 2);
            })
            ->editColumn('maximum_discount_amount', function($promotion) {
                return number_format($promotion->maximum_discount_amount, 2);
            })
            ->editColumn('usage_limit', function($promotion) {
                return $promotion->usage_limit ?? 'Unlimited';
            })
            ->editColumn('used_count', function($promotion) {
                return $promotion->used_count;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promotions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Promotion $promotion)
    {
        return view('admin.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'discount' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive'
        ]);

        $promotion->update([
            'name' => $request->name,
            'discount' => $request->discount,
            'status' => $request->status
        ]);

        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Toggle promotion status
     */
    public function toggleStatus(Promotion $promotion)
    {
        $promotion->status = $promotion->status === 'active' ? 'inactive' : 'active';
        $promotion->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Promotion status updated successfully',
            'new_status' => $promotion->status
        ]);
    }
}
