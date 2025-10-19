<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubOrder;
use Illuminate\Http\Request;

class SubOrderController extends Controller
{
    public function index()
    {
        $subOrders = SubOrder::all();
        return view('admin.sub_orders.index', compact('subOrders'));
    }

    public function create()
    {
        return view('admin.sub_orders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
        ]);

        SubOrder::create($request->all());
        return redirect()->route('admin.sub_orders.index')->with('success', 'SubOrder created successfully.');
    }

    public function show(SubOrder $subOrder)
    {
        return view('admin.sub_orders.show', compact('subOrder'));
    }

    public function edit(SubOrder $subOrder)
    {
        return view('admin.sub_orders.edit', compact('subOrder'));
    }

    public function update(Request $request, SubOrder $subOrder)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $subOrder->update($request->all());
        return redirect()->route('admin.sub_orders.index')->with('success', 'SubOrder updated successfully.');
    }

    public function destroy(SubOrder $subOrder)
    {
        $subOrder->delete();
        return redirect()->route('admin.sub_orders.index')->with('success', 'SubOrder deleted successfully.');
    }
}
