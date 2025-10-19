<?php

namespace App\Services\Product;

use App\Http\Resources\ProductResource;
use App\Http\Resources\SupplierResource;
use App\Services\BaseService;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService extends BaseService
{
    /**
     * Get products list with filters
     */
    public function getProducts(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'category_id' => 'nullable|exists:categories,id',
                'search' => 'nullable|string',
                'supplier_id' => 'nullable|exists:suppliers,id',
                'per_page' => 'nullable|integer|min:1',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $products = Product::query()
                ->when(isset($data['category_id']), function ($query) use ($data) {
                    $query->where('category_id', $data['category_id']);
                })
                ->when(isset($data['search']), function ($query) use ($data) {
                    $query->where(function ($q) use ($data) {
                        $q->where('name_en', 'like', "%{$data['search']}%")
                            ->orWhere('name_ar', 'like', "%{$data['search']}%")
                            ->orWhere('sku', 'like', "%{$data['search']}%");
                    });
                })
                ->when(isset($data['supplier_id']), function ($query) use ($data) {
                    $query->whereHas('suppliers', function ($q) use ($data) {
                        $q->where('suppliers.id', $data['supplier_id'])
                            ->where('supplier_products.is_active', true);
                    });
                })
                ->with(['category', 'suppliers' => function ($query) {
                    $query->where('supplier_products.is_active', true);
                }])
                ->paginate($data['per_page'] ?? 15);

            return $this->successResponse(
                data: ProductResource::collection($products),
                message: __('api.products_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create a new product
     */
    public function createProduct(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'category_id' => 'required|exists:categories,id',
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sku' => 'required|string|unique:products',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $product = Product::create($data);

            return $this->successResponse(
                data: ProductResource::make($product),
                message: __('api.product_created'),
                statusCode: 201
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get product details
     */
    public function getProductDetails(Product $product): JsonResponse
    {
        try {
            $product->load(['category', 'suppliers' => function ($query) {
                $query->where('supplier_products.is_active', true);
            }]);

            return $this->successResponse(
                data: ProductResource::make($product),
                message: __('api.product_details_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update product
     */
    public function updateProduct(array $data, Product $product): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'category_id' => 'sometimes|required|exists:categories,id',
                'name_en' => 'sometimes|required|string|max:255',
                'name_ar' => 'sometimes|required|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'price' => 'sometimes|required|numeric|min:0',
                'sku' => 'sometimes|required|string|unique:products,sku,' . $product->id,
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $product->update($data);

            return $this->successResponse(
                data: ProductResource::make($product),
                message: __('api.product_updated')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Delete product
     */
    public function deleteProduct(Product $product): JsonResponse
    {
        try {
            if ($product->orderItems()->exists()) {
                throw ValidationException::withMessages([
                    'product' => ['Cannot delete product with existing orders.'],
                ]);
            }

            $product->delete();

            return $this->successResponse(
                message: __('api.product_deleted')
            );
        } catch (ValidationException $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                hint: 'Product has existing orders and cannot be deleted',
                statusCode: 422
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get product suppliers
     */
    public function getProductSuppliers(Product $product): JsonResponse
    {
        try {
            $suppliers = $product->suppliers()
                ->where('supplier_products.is_active', true)
                ->with('user')
                ->get();

            return $this->successResponse(
                data: SupplierResource::collection($suppliers),
                message: __('api.product_suppliers_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
