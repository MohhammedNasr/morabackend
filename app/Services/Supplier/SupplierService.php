<?php

namespace App\Services\Supplier;

use App\Http\Resources\GroupedSubOrderResource;
use App\Services\BaseService;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\Representative;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\RepresentativeResource;
use App\Http\Resources\SubOrderDetailResource;
use App\Http\Resources\SubOrderResource;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\SubOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SupplierService extends BaseService
{
    /**
     * Verify a supplier
     */
    public function verifySupplier(Supplier $supplier, string $verificationCode): JsonResponse
    {
        try {
            if (empty($supplier->verification_code)) {
                throw ValidationException::withMessages([
                    'verification' => ['No verification code exists for this supplier'],
                ]);
            }

            if ($supplier->verification_code !== $verificationCode) {
                throw ValidationException::withMessages([
                    'verification' => ['The verification code does not match'],
                ]);
            }

            $supplier->update([
                'is_verified' => 1,
                'verified_at' => now(),
                'verification_code' => null
            ]);
            // Generate token for auto-login after verification
            $token = $supplier->createToken('auth-token')->plainTextToken;

            return $this->successResponse(
                data: [
                    'token' => $token,
                    'user' => new UserResource($supplier),
                    'supplier' => new SupplierResource($supplier),
                    'is_verified' =>  $supplier->is_verified
                ],
                message: __('api.supplier_verified')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Check supplier verification status
     */
    public function checkVerificationStatus(Supplier $supplier): JsonResponse
    {
        return $this->successResponse(
            data: [
                'is_verified' => $supplier->is_verified,
                'verified_at' => $supplier->verified_at
            ],
                message: __('api.verification_status_retrieved')
        );
    }

    /**
     * Resend verification code to supplier
     */
    public function resendVerification(Supplier $supplier): JsonResponse
    {
        try {
            $verificationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            $supplier->update([
                'verification_code' => $verificationCode,
                'is_verified' => false,
                'verified_at' => null
            ]);
            $this->sendSms($supplier->phone, 'Your verification code has been resent: ' . $verificationCode);
            return $this->successResponse(
                message: __('api.verification_code_resent'),
                data: [
                    'verification_code' => app()->environment('local') ? $verificationCode : null // Remove in production
                ]
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get suppliers list with filters
     */
    public function getSuppliers(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'search' => 'nullable|string',
                'is_active' => 'nullable|boolean',
                'per_page' => 'nullable|integer|min:1',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $suppliers = Supplier::query()
                ->when(isset($data['search']), function ($query) use ($data) {
                    $query->where(function ($q) use ($data) {
                        $q->where('name', 'like', "%{$data['search']}%")
                            ->orWhere('commercial_record', 'like', "%{$data['search']}%");
                    });
                })
                ->when(isset($data['is_active']), function ($query) use ($data) {
                    $query->where('is_active', $data['is_active']);
                })
                ->paginate($data['per_page'] ?? 15);

            return $this->successResponse(
                data: SupplierResource::collection($suppliers),
                message: __('api.suppliers_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Create a new supplier
     */
    public function createSupplier(array $data): JsonResponse
    {
        try {
            $verificationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $supplier = Supplier::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'commercial_record' => $data['commercial_record'],
                'payment_term_days' => $data['payment_term_days'] ?? 60,
                'address' => $data['address'],
                'contact_name' => $data['contact_name'] ?? null,
                'is_verified' => false,
                'verified_at' => null,
                'verification_code' => $verificationCode,
                'role_id' => 3,
                // Optional fields
                'tax_id' => $data['tax_id'] ?? null,
                'bank_account' => $data['bank_account'] ?? null,
                'iban_number' => $data['iban_number'],
                'id_number' => $data['id_number'],
                'bank_id' => $data['bank_id'],
                'account_owner_name' => $data['account_owner_name'] ?? null,
                'website' => $data['website'] ?? null,
                "is_active" => 0
            ]);

            // Generate token
            $token = $supplier->createToken('supplier-token')->plainTextToken;
            // Send SMS with the verification code
            $this->sendSms($supplier->phone, 'Your verification code is: ' . $verificationCode);

            return $this->successResponse(
                data: [
                    'user' => [
                        'id' => $supplier->id,
                        'name' => $supplier->name,
                        'role' => 'supplier',
                        'role_name' => 'Supplier',
                        'email' => $supplier->email,
                        'phone' => $supplier->phone,
                        'created_at' => $supplier->created_at,
                        'updated_at' => $supplier->updated_at,
                    ],
                    'supplier' => new SupplierResource($supplier),
                    'code' => $verificationCode

                ],
                message: __('api.supplier_registered'),
                statusCode: 201
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get supplier details
     */
    public function getSupplierDetails(Supplier $supplier): JsonResponse
    {
        try {
            $supplier->load(['product' => function ($query) {
                $query->where('supplier_product.is_active', true);
            }]);

            return $this->successResponse(
                data: $supplier,
                message: __('api.supplier_details_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update supplier
     */
    public function updateSupplier(array $data, Supplier $supplier): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'name' => 'sometimes|required|string|max:255',
                'commercial_record' => 'sometimes|required|string|unique:suppliers,commercial_record,' . $supplier->id,
                'payment_term_days' => 'sometimes|required|integer|min:1|max:365',
                'is_active' => 'sometimes|required|boolean',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $supplier->update($data);

            return $this->successResponse(
                data: [
                    'user' => new UserResource($supplier),
                    'supplier' => new SupplierResource($supplier)
                ],
                message: __('api.supplier_updated')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get supplier products
     */
    public function getSupplierProducts(Supplier $supplier): JsonResponse
    {
        try {
            $products = $supplier->products()
                ->with('category')
                ->paginate(15);

            return $this->successResponse(
                data: $products,
                message: __('api.supplier_products_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Attach product to supplier
     */
    public function attachProduct(array $data, Supplier $supplier): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'product_id' => 'required|exists:products,id',
                'price' => 'required|numeric|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $product = Product::findOrFail($data['product_id']);

            return DB::transaction(function () use ($supplier, $product, $data) {
                $supplier->products()->syncWithoutDetaching([
                    $product->id => [
                        'price' => $data['price'],
                        'is_active' => $data['is_active'] ?? true,
                    ],
                ]);

                return $this->successResponse(
                    data: ['supplier' => $supplier->load('products')],
                    message: __('api.product_attached')
                );
            });
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Update supplier product
     */
    public function updateProduct(array $data, Supplier $supplier, Product $product): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'price' => 'sometimes|required|numeric|min:0',
                'is_active' => 'sometimes|required|boolean',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            if (!$supplier->products()->where('product_id', $product->id)->exists()) {
                throw ValidationException::withMessages([
                    'product' => ['This product is not associated with the supplier.'],
                ]);
            }

            $supplier->products()->updateExistingPivot($product->id, array_filter($data));

            return $this->successResponse(
                data: ['supplier' => $supplier->load('products')],
                message: __('api.product_updated')
            );
        } catch (ValidationException $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                hint: 'Please check if the product is associated with the supplier',
                statusCode: 422
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Detach product from supplier
     */
    public function detachProduct(Supplier $supplier, Product $product): JsonResponse
    {
        try {
            if ($supplier->orders()->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })->exists()) {
                throw ValidationException::withMessages([
                    'product' => ['Cannot detach product with existing orders.'],
                ]);
            }

            $supplier->products()->detach($product->id);

            return $this->successResponse(
                message: __('api.product_detached')
            );
        } catch (ValidationException $e) {
            return $this->errorResponse(
                message: $e->getMessage(),
                hint: 'Product has existing orders and cannot be detached',
                statusCode: 422
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get supplier orders
     */
    // deprecated since supplier has only sub orders
    public function getSupplierOrders(Supplier $supplier): JsonResponse
    {
        try {
            $orders = $supplier->orders()
                ->with(['store', 'items.product'])
                ->latest()
                ->paginate(15);

            return $this->successResponse(
                data: $orders,
                message: 'Supplier orders retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get supplier representatives
     */
    public function listRepresentatives($supplier, array $data = [])
    {
        try {
            $validationResult = $this->validateData($data, [
                'per_page' => 'nullable|integer|min:1',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $representatives = $supplier->representatives()
                ->paginate($data['per_page'] ?? 15);

            return $this->successResponse(
                data: RepresentativeResource::collection($representatives),
                message: __('api.representatives_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Register a new representative for the supplier
     */
    public function registerRepresentative(Supplier $supplier, array $data): JsonResponse
    {

        try {

            $representative = $supplier->representatives()->create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'role_id' => 2, // Representative role
                'is_active' => true
            ]);

            return $this->successResponse(
                data: new RepresentativeResource($representative),
                message: __('api.representative_registered'),
                statusCode: 201
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function getRepresentativeDetails(Representative $representative): JsonResponse
    {
        try {
            return $this->successResponse(
                data: new RepresentativeResource($representative),
                message: __('api.representative_details_retrieved')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function updateRepresentative(Representative $representative, array $data): JsonResponse
    {
        try {
            if (isset($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }

            $representative->update($data);

            return $this->successResponse(
                data: new RepresentativeResource($representative->fresh()),
                message: __('api.representative_updated')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function deleteRepresentative(Supplier $supplier, Representative $representative): JsonResponse
    {
        try {
            // Verify representative belongs to supplier
            if ($representative->supplier_id !== $supplier->id) {
                return $this->errorResponse(
                    message: 'Unauthorized to delete this representative',
                    statusCode: 403
                );
            }

            // Check if representative has assigned orders
            if ($representative->subOrders()->exists()) {
                return $this->errorResponse(
                    message: 'Cannot delete representative with assigned orders',
                    hint: 'Please reassign orders before deletion',
                    statusCode: 422
                );
            }

            $representative->delete();

            return $this->successResponse(
                message: __('api.representative_deleted')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get suppliers by categories
     */
    public function getSuppliersByCategories(array $data): JsonResponse
    {
        try {
            $suppliers = Supplier::query()
                ->whereHas('products', function ($query) use ($data) {
                    $query->whereIn('category_id', $data['categories_ids']);
                })
                ->when(isset($data['search']), function ($query) use ($data) {
                    $query->where(function ($q) use ($data) {
                        $q->where('name', 'like', "%{$data['search']}%")
                            ->orWhere('commercial_record', 'like', "%{$data['search']}%");
                    });
                })
                ->when(isset($data['is_active']), function ($query) use ($data) {
                    $query->where('is_active', $data['is_active']);
                })
                ->with(['products' => function ($query) use ($data) {
                    $query->whereIn('category_id', $data['categories_ids']);
                    $query->with('category');
                }])->with('categories')
                ->paginate($data['per_page'] ?? 15);

            return $this->successResponse(
                data: SupplierResource::collection($suppliers),
                message: 'Suppliers retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Get supplier suborders
     */
    /**
     * Assign a representative to a sub-order
     */
    public function assignRepresentative(SubOrder $subOrder, Representative $representative): JsonResponse
    {
        try {
            // Verify representative belongs to the same supplier
            if ($representative->supplier_id !== $subOrder->supplier_id) {
                return $this->errorResponse(
                    message: 'Representative does not belong to the same supplier',
                    statusCode: 403
                );
            }

            return DB::transaction(function () use ($subOrder, $representative) {
                $subOrder->update([
                    'representative_id' => $representative->id,
                    'status' => SubOrder::STATUS_ASSIGNED_TO_REP
                ]);

                // Create timeline entry
                $subOrder->timelines()->create([
                    'status' => SubOrder::STATUS_ASSIGNED_TO_REP,
                    'description' => 'Assigned to representative: ' . $representative->name,
                    'created_by_type' => 'supplier',
                    'created_by_id' => auth('supplier')->id()
                ]);

                return $this->successResponse(
                    data: new SubOrderDetailResource($subOrder->fresh()),
                    message: 'Representative assigned successfully'
                );
            });
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
