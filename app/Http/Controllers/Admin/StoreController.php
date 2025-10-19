<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class StoreController extends Controller
{
    public function create()
    {
        return view('admin.stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'type' => 'required|in:hypermarket,supermarket,restaurant',
            'commercial_record' => 'required|string|max:255',
            'tax_number' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
        ]);

        // Create owner user
        $owner = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role_id' => \App\Models\Role::where('name', 'store_owner')->first()->id,
        ]);

        // Create store
        $store = Store::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
            'commercial_record' => $request->commercial_record,
            'tax_number' => $request->tax_number,
            'id_number' => $request->id_number,
            'credit_limit' => 0,
            'is_verified' => $request->has('is_verified'),
            'owner_id' => $owner->id,
        ]);

        // Attach owner to store
        $store->users()->attach($owner->id, ['is_primary' => true]);

        return redirect()
            ->route('admin.stores.index')
            ->with('success', 'Store created successfully.');
    }

    public function index()
    {
        return view('admin.stores.index');
    }

    public function updateStatus(Request $request, Store $store)
    {
        $request->validate([
            'field' => 'required|in:is_active,is_verified,auto_verify_order',
            'value' => 'required|boolean'
        ]);

        if (in_array($request->field, ['is_active', 'is_verified'])) {
            $store->owner()->update([
                $request->field => $request->value ? 1 : 0
            ]);
        } else {
            $store->update([
                $request->field => (bool)$request->value ? 1 : 0
            ]);
        }

        return response()->json([
            'message' => __('messages.stores.status_updated')
        ]);
    }

    public function showImportForm(Store $store)
    {
        return view('admin.stores.import-products', compact('store'));
    }

    public function importProducts(Request $request, Store $store)
    {
        $request->validate([
            'products_file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ProductsImport($store), $request->file('products_file'));
            return redirect()->back()->with('success', __('Products imported successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $stores = Store::query()
            ->with(['users' => function ($query) {
                $query->wherePivot('is_primary', true);
            }, 'wallet', 'branches', 'orders'])
            ->select([
                'stores.id',
                'stores.name',
                'stores.email',
                'stores.phone',
                'stores.commercial_record',
                'stores.credit_limit',
                'stores.is_verified',
                'stores.auto_verify_order',
                'stores.created_at',
                'users.name as owner_name',
                'users.email as owner_email'
            ])
            ->join('store_users', 'stores.id', '=', 'store_users.store_id')
            ->join('users', 'store_users.user_id', '=', 'users.id')
            ->where('store_users.is_primary', true);

        return DataTables::of($stores)
            ->addColumn('status', function ($store) {
                return $store->is_verified
                    ? '<span class="kt-badge kt-badge--success kt-badge--inline">Verified</span>'
                    : '<span class="kt-badge kt-badge--warning kt-badge--inline">Pending</span>';
            })
            ->addColumn('credit_limit', function ($store) {
                return number_format($store->credit_limit, 2);
            })
            ->addColumn('orders_count', function ($store) {
                return $store->orders()->count();
            })
            ->addColumn('branches_count', function ($store) {
                return $store->branches()->count();
            })
            ->addColumn('actions', function ($store) {
                return view('admin.stores.partials.actions', compact('store'))->render();
            })
            ->editColumn('created_at', function ($store) {
                return $store->created_at?->format('Y-m-d H:i') ?? '';
            })
            ->rawColumns(['status', 'actions'])
            ->make(true);
    }

    public function show(Request $request, $id)
    {
        $store = Store::with([
            'users' => function ($query) {
                $query->wherePivot('is_primary', true);
            },
            'wallet',
            'branches.city',
            'branches.area',
            'orders' => function ($query) {
                $query->latest()->take(5);
            },
        ])->find($id);

        if (!$store) {
            abort(404, 'Store not found');
        }

        // Return JSON for API requests
        if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $owner = $store->users->first();
            
            return response()->json([
                'id' => $store->id,
                'name' => $store->name,
                'email' => $store->email,
                'phone' => $store->phone,
                'commercial_record' => $store->commercial_record,
                'tax_number' => $store->tax_number,
                'credit_limit' => $store->credit_limit,
                'is_verified' => $store->is_verified ? true : false,
                'auto_verify_order' => $store->auto_verify_order ? true : false,
                'created_at' => $store->created_at?->format('Y-m-d H:i:s'),
                'owner_name' => $owner?->name,
                'owner_email' => $owner?->email,
                'branches' => $store->branches->map(function($branch) {
                    return [
                        'id' => $branch->id,
                        'name' => $branch->name,
                        'phone' => $branch->phone,
                        'street_name' => $branch->street_name,
                        'balance_limit' => (float) $branch->balance_limit,
                        'is_active' => $branch->is_active ? true : false,
                        'city' => $branch->city ? ['name' => $branch->city->name] : null,
                        'area' => $branch->area ? ['name' => $branch->area->name] : null,
                    ];
                }),
                'orders_count' => $store->orders()->count(),
                'wallet' => $store->wallet ? [
                    'balance' => (float) $store->wallet->balance,
                ] : null,
            ]);
        }

        return view('admin.stores.show', compact('store'));
    }

    public function edit($id)
    {
        $store = Store::with([
            'users' => function ($query) {
                $query->wherePivot('is_primary', true);
            },
            'wallet'
        ])->find($id);

        if (!$store) {
            abort(404, 'Store not found');
        }

        return view('admin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:stores,email,' . $store->id,
            'phone' => 'required|string|unique:stores,phone,' . $store->id,
            'credit_limit' => 'required|numeric|min:5000|max:500000',
        ]);

        $store->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'credit_limit' => $request->credit_limit,
        ]);

        $store->users()->wherePivot('is_primary', true)->first()->update([

            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('admin.stores.index')
            ->with('success', 'Store updated successfully.');
    }

    public function destroy(Store $store)
    {
        if ($store->orders()->exists()) {
            return back()->with('error', 'Cannot delete store with existing orders.');
        }

        $store->wallet()->delete();
        $store->users()->wherePivot('is_primary', true)->first()->delete();

        $store->delete();

        return redirect()
            ->route('admin.stores.index')
            ->with('success', 'Store deleted successfully.');
    }
}
