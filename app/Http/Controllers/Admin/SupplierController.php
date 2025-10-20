<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('commercial_record', 'like', "%{$search}%");
                });
            })
            ->with(['user', 'products'])
            ->paginate(10);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed:confirm_password', Password::defaults()],
            'phone' => 'required|string|max:20|regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/',
            'commercial_record' => 'required|string|unique:suppliers',
            'payment_term_days' => 'required|integer|min:1|max:365',
            'contact_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:50',
            'bank_account' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'is_active' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $supplier = Supplier::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'commercial_record' => $request->commercial_record,
                'payment_term_days' => $request->payment_term_days,
                'contact_name' => $request->contact_name,
                'tax_id' => $request->tax_id,
                'bank_account' => $request->bank_account,
                'website' => $request->website,
                'address' => $request->address,
                'is_active' => $request->is_active ?? 1,
                'role_id' => 3,
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier created successfully',
                    'supplier' => $supplier
                ]);
            }

            return redirect()
                ->route('admin.suppliers.index')
                ->with('success', 'Supplier created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function edit(Supplier $supplier)
    {
        $supplier->load('user');
        
        if (request()->expectsJson()) {
            return response()->json([
                'supplier' => $supplier
            ]);
        }
        
        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['user', 'products']);
        return view('admin.suppliers.show', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $supplier->user_id,
            'phone' => 'required|string|max:20|regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/',
            'commercial_record' => 'required|string|unique:suppliers,commercial_record,' . $supplier->id,
            'payment_term_days' => 'required|integer|min:1|max:365',
            'contact_name' => 'required|string|max:255',
            'tax_id' => 'required|string|max:50',
            'bank_account' => 'nullable|string|max:100',
            'website' => 'nullable|url|max:255',
            'address' => 'required|string|max:500',
            'is_active' => 'required|boolean',
            'password' => ['nullable', 'confirmed:password_confirmation', Password::defaults()],
        ]);

        try {
            DB::beginTransaction();
            
            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'commercial_record' => $request->commercial_record,
                'payment_term_days' => $request->payment_term_days,
                'contact_name' => $request->contact_name,
                'tax_id' => $request->tax_id,
                'bank_account' => $request->bank_account,
                'website' => $request->website,
                'address' => $request->address,
                'is_active' => $request->is_active,
            ];
            
            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }
            
            $supplier->update($updateData);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier updated successfully'
                ]);
            }

            return redirect()
                ->route('admin.suppliers.index')
                ->with('success', 'Supplier updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->orders()->exists()) {
            return back()->with('error', 'Cannot delete supplier with existing orders.');
        }

        try {
            DB::beginTransaction();

            $supplier->products()->detach();
            $supplier->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Supplier deleted successfully'
                ]);
            }

            return redirect()
                ->route('admin.suppliers.index')
                ->with('success', 'Supplier deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function datatable(Request $request)
    {
        $suppliers = Supplier::query()
            ->with(['user', 'products'])
            ->select([
                'id',
                'name',
                'contact_name',
                'email',
                'phone',
                'address',
                'commercial_record',
                'payment_term_days',
                'is_active',
                'is_verified',
                'created_at',
            ]);

        return datatables()->eloquent($suppliers)
            ->editColumn('created_at', function ($supplier) {
                return $supplier->created_at->format('Y-m-d H:i');
            })
            ->make(true);
    }

    public function importProducts(Request $request, Supplier $supplier)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            DB::beginTransaction();

            Log::info('Starting product import for supplier: ' . $supplier->id);
            $import = new \App\Imports\ProductsSpreadsheetImport($supplier->id);
            $filePath = $request->file('file')->getRealPath();
            $importResult = $import->import($filePath);

            DB::commit();

            Log::info('Import completed', [
                'success' => $import->success(),
                'errors' => $import->errors()
            ]);

            if ($import->success()) {
                return redirect()->back()->with('success', $import->getMessage());
            }

            return redirect()->back()
                ->with('error', $import->getMessage())
                ->with('import_errors', $import->errors());

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product import failed: ' . $e->getMessage(), [
                'exception' => $e,
                'supplier_id' => $supplier->id,
                'file' => $request->file('file')->getClientOriginalName()
            ]);
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Supplier $supplier)
    {
        $request->validate([
            'field' => 'required|in:is_active,is_verified',
            'value' => 'required|boolean'
        ]);

        $supplier->update([
            $request->field => $request->value
        ]);

        return response()->json([
            'message' => 'Supplier status updated successfully'
        ]);
    }

    public function list(Request $request)
    {
        $suppliers = Supplier::select('id', 'name')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return response()->json($suppliers);
    }
}
