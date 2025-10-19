<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $suppliers = Supplier::where('is_active', true)->get();
        $products = Product::with(['category', 'supplier'])->get();
        return view('admin.products.index', compact('categories', 'suppliers', 'products'));
    }

    public function showImportForm()
    {

        $suppliers = Supplier::where('is_active', true)->get();
        return view('admin.products.import', compact('suppliers'));
    }


    public function datatable(Request $request)
    {
        Log::debug('Datatable request received', [
            'search' => $request->search,
            'status' => $request->status,
            'category' => $request->category
        ]);

        $products = Product::query()
            ->with(['category'])
            ->when($request->search && isset($request->search['value']), function ($query) use ($request) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                        ->orWhere('name_ar', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function ($query, $status) {
                if ($status === 'active') {
                    $query->whereNull('deleted_at');
                } elseif ($status === 'deleted') {
                    $query->whereNotNull('deleted_at');
                }
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            });

        return DataTables::of($products)
            ->addColumn('image', function ($product) {
                // return $product->image ?? null;
                if ($product->image && file_exists(public_path($product->image))) {
                    return '<img src="' . asset($product->image) . '" alt="' . $product->name_en . '" style="max-height: 50px; max-width: 50px;" class="img-thumbnail">';
                }
                return '<span class="text-muted">No image</span>';
            })
            ->addColumn('status', function ($product) {
                return $product->deleted_at ?
                    '<span class="kt-badge kt-badge--danger kt-badge--inline kt-badge--pill">Deleted</span>' :
                    '<span class="kt-badge kt-badge--success kt-badge--inline kt-badge--pill">Active</span>';
            })
            ->addColumn('actions', function ($product) {
                return '
                    <a href="' . route('admin.products.show', $product) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        <i class="la la-eye"></i>
                    </a>
                    <a href="' . route('admin.products.edit', $product) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                        <i class="la la-edit"></i>
                    </a>
                    <form action="' . route('admin.products.destroy', $product) . '" method="POST" class="d-inline" style="display:inline;">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="return confirm(\'Are you sure you want to delete this product?\')">
                            <i class="la la-trash"></i>
                        </button>
                    </form>';
            })
            ->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::where('is_active', true)->get();
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'sku' => 'required|string|unique:products',
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productData = [
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'sku' => $request->sku,
            'price' => $request->price,
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $productData['image'] = 'images/products/' . $imageName;
        }

        $product = Product::create($productData);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('supplier');
        $categories = Category::all();
        $suppliers = Supplier::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'supplier_id' => 'required|exists:suppliers,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'sku' => $request->sku,
            'price' => $request->price,
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/products'), $imageName);
            $updateData['image'] = 'images/products/' . $imageName;
        }

        $product->update($updateData);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Cannot delete product with existing orders.');
        }

        $product->suppliers()->detach();
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        return view('admin.products.show', compact('product'));
    }

    public function export()
    {
        return Excel::download(new ProductsExport(), 'products_' . date('Y-m-d') . '.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
            'supplier_id' => 'required|exists:suppliers,id'
        ]);

        $import = new ProductsImport($request->supplier_id);

        try {
            Excel::import($import, $request->file('file'));

            $failures = $import->failures();
            if ($failures->isNotEmpty()) {
                $errors = [];
                foreach ($failures as $failure) {
                    $row = $failure->row();
                    $errors[] = "Row {$row}: " . implode(', ', $failure->errors());
                }
                return back()
                    ->with('import_errors', $errors)
                    ->with('error', 'Some rows failed validation. See details below.');
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Products imported successfully!');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->with('import_errors', $import->errors());
        }
    }
}
