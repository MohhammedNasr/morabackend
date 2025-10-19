<?php

namespace App\Services\Category;

use App\Services\BaseService;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SupplierResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class CategoryService extends BaseService
{
    /**
     * List all categories.
     */
    public function listCategories(array $data): JsonResponse
    {
        try {
            $query = Category::query()->isActive();
            $user = Auth::user();

            // Auto-filter for supplier users
            if ($user && $user->role && $user->role->slug === 'supplier') {
                $query->whereHas('products', function ($q) use ($user) {
                    $q->where('supplier_id', $user->id)
                        ->where('status', 'active');
                });
            }
            // Auto-filter for representative users
            elseif ($user && $user->role && $user->role->slug === 'representative') {
                $query->whereHas('products.supplier', function ($q) use ($user) {
                    $q->whereHas('representatives', function ($q2) use ($user) {
                        $q2->where('id', $user->id);
                    });
                });
            }
            // Manual filter fallback
            else {
                if (isset($data['supplier_id'])) {
                    $query->whereHas('products', function ($q) use ($data) {
                        $q->where('supplier_id', $data['supplier_id'])
                            ->where('status', 'active');
                    });
                }

                if (isset($data['representative_id'])) {
                    $query->whereHas('products.supplier', function ($q) use ($data) {
                        $q->where('representative_id', $data['representative_id']);
                    });
                }
            }

            $categories = $this->paginate($query, $data);

            // Use CategoryResource to transform the data
            $categoryResources = CategoryResource::collection($categories->items());

            return $this->successResponse(
                data: [
                    'categories' => $categoryResources,
                    'pagination' => [
                        'current_page' => $categories->currentPage(),
                        'per_page' => $categories->perPage(),
                        'total' => $categories->total(),
                        'last_page' => $categories->lastPage(),
                    ],
                ],
                message: __('api.categories_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get products by categories.
     */
    public function getProductsByCategories(array $data): JsonResponse
    {
        try {
            $query = Product::with(['category', 'supplier'])
                ->whereIn('category_id', $data['categories']);

            // Filter by supplier if provided
            if (isset($data['suppliers'])) {
                $query->whereIn('supplier_id', $data['suppliers']);
            }

            // Search by product name if provided
            if (isset($data['search'])) {
                $locale = app()->getLocale();
                $query->where("name_$locale", 'like', '%' . $data['search'] . '%');
            }


            // $products = $query->select([
            //     'id',
            //     'name_' . app()->getLocale() . ' as name',
            //     'image',
            //     'price',
            //     'sku',
            //     'available_quantity',
            //     'category_id',
            // ]);

            $paginatedProducts = $this->paginate($query, $data);

            // Transform products to include full image URL
            $transformedProducts = collect($paginatedProducts->items())->map(function ($product) {
                $product->image = $product->image ? asset($product->image) : null;
                return $product;
            });

            return $this->successResponse(
                data: [
                    'products' => ProductResource::collection($transformedProducts),
                    'pagination' => [
                        'current_page' => $paginatedProducts->currentPage(),
                        'per_page' => $paginatedProducts->perPage(),
                        'total' => $paginatedProducts->total(),
                        'last_page' => $paginatedProducts->lastPage(),
                    ],
                ],
                message: __('api.products_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get suppliers by category.
     */
    public function getSuppliersByCategory(int $categoryId): JsonResponse
    {
        try {
            $category = Category::findOrFail($categoryId);

            $suppliers = Supplier::whereHas('products', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId)
                    ->where('products.status', 'active');
            })
                ->where('is_active', true)
                ->with(['products' => function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId)
                        ->select(['id', 'name_' . app()->getLocale() . ' as name', 'image', 'price']);
                }])
                ->get();

            return $this->successResponse(
                data: [
                    'category' => new CategoryResource($category),
                    'suppliers' => SupplierResource::collection($suppliers)
                ],
                message: __('api.category_suppliers_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
