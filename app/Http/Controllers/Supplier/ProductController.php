<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function index()
    {
        $supplier = auth()->guard('supplier-web')->user();
        $categories = Category::where('status', 'active')->get();
        return view('supplier.products.index', compact('categories'));
    }

    public function datatable(Request $request)
    {
        $supplier = auth()->guard('supplier-web')->user();

        $products = Product::query()
            ->where('supplier_id', $supplier->id)
            ->with(['category'])
            ->when($request->search && isset($request->search['value']), function ($query) use ($request) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                        ->orWhere('name_ar', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            });

        return DataTables::of($products)
            ->addColumn('image', function ($product) {
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
                    <a href="' . route('supplier.products.edit', $product) . '" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                        <i class="la la-edit"></i>
                    </a>
                    <form action="' . route('supplier.products.destroy', $product) . '" method="POST" class="d-inline" style="display:inline;">
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
        $categories = Category::where('status', 'active')->get();
        return view('supplier.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $supplier = auth()->guard('supplier-web')->user();

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'sku' => 'required|string|unique:products',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $productData = [
            'category_id' => $request->category_id,
            'supplier_id' => $supplier->id,
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

        Product::create($productData);

        return redirect()
            ->route('supplier.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $supplier = auth()->guard('supplier-web')->user();
        if ($product->supplier_id !== $supplier->id) {
            abort(403);
        }

        $categories = Category::where('status', 'active')->get();
        return view('supplier.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $supplier = auth()->guard('supplier-web')->user();
        if ($product->supplier_id !== $supplier->id) {
            abort(403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $updateData = [
            'category_id' => $request->category_id,
            'name_en' => $request->name_en,
            'name_ar' => $request->name_ar,
            'description_en' => $request->description_en,
            'description_ar' => $request->description_ar,
            'sku' => $request->sku,
            'price' => $request->price,
        ];

        if ($request->hasFile('image')) {
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
            ->route('supplier.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $supplier = auth()->guard('supplier-web')->user();
        if ($product->supplier_id !== $supplier->id) {
            abort(403);
        }

        if ($product->orderItems()->exists()) {
            return back()->with('error', 'Cannot delete product with existing orders.');
        }

        $product->delete();

        return redirect()
            ->route('supplier.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function showImportForm()
    {
        return view('supplier.products.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $supplier = auth()->guard('supplier-web')->user();

        $import = new ProductsImport($supplier->id);
        try {
            Excel::import($import, $request->file('file'));
            $failures = $import->failures();
            if ($failures->isNotEmpty()) {
                $errors = [];
                foreach ($failures as $failure) {
                    $row = $failure->row();
                    $errors[] = "Row {$row}: " . implode(', ', $failure->errors());
                }
                    // dd($errors);
                return back()
                    ->with('import_errors', $errors)
                    ->with('error', 'Some rows failed validation. See details below.');
            }


            return redirect()
                ->route('supplier.products.index')
                ->with('success', __('messages.products.import_success'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::error('Product import error: ' . $e->getMessage());
            return back()
                ->with('error', 'Import failed: ' . $e->getMessage())
                ->with('import_errors', $import->errors());
        }
    }
}
