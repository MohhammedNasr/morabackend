<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\ListSupplierRequest;
use App\Http\Requests\Supplier\ListSuppliersByCategoriesRequest;
use App\Http\Requests\Supplier\CreateSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Requests\Supplier\AttachProductRequest;
use App\Http\Requests\Supplier\UpdateProductRequest;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SubOrder;
use App\Models\Representative;
use App\Services\Supplier\SupplierService;
use App\Services\Order\SubOrderService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Supplier\RegisterRepresentativeRequest;
use App\Http\Requests\Supplier\UpdateRepresentativeRequest;
use App\Http\Resources\SubOrderResource;

class SupplierController extends Controller
{
    protected SupplierService $supplierService;
    protected SubOrderService $orderService;

    public function __construct(SupplierService $supplierService, SubOrderService $orderService)
    {
        $this->supplierService = $supplierService;
        $this->orderService = $orderService;
    }
    public function index(ListSupplierRequest $request): JsonResponse
    {
        return $this->supplierService->getSuppliers($request->all());
    }

    public function store(CreateSupplierRequest $request): JsonResponse
    {
        return $this->supplierService->createSupplier($request->all());
    }

    public function show(Supplier $supplier): JsonResponse
    {
        return $this->supplierService->getSupplierDetails($supplier);
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
        return $this->supplierService->updateSupplier($request->all(), $supplier);
    }

    public function products(Supplier $supplier): JsonResponse
    {
        return $this->supplierService->getSupplierProducts($supplier);
    }

    public function attachProduct(AttachProductRequest $request, Supplier $supplier): JsonResponse
    {
        return $this->supplierService->attachProduct($request->all(), $supplier);
    }

    public function updateProduct(UpdateProductRequest $request, Supplier $supplier, Product $product): JsonResponse
    {
        return $this->supplierService->updateProduct($request->all(), $supplier, $product);
    }

    public function detachProduct(Supplier $supplier, Product $product): JsonResponse
    {
        return $this->supplierService->detachProduct($supplier, $product);
    }

    public function orders(Supplier $supplier): JsonResponse
    {
        return $this->supplierService->getSupplierOrders($supplier);
    }

    public function byCategories(ListSuppliersByCategoriesRequest $request): JsonResponse
    {
        return $this->supplierService->getSuppliersByCategories($request->all());
    }

    public function assignRepresentative($supplier, $subOrder, $representative): JsonResponse
    {
        $subOrder = SubOrder::where('id', (int)$subOrder)->first();
        $representative = Representative::where('id', (int)$representative)->first();
        $supplier = auth('supplier')->user();

        if (!$supplier) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->supplierService->assignRepresentative($subOrder, $representative);
    }

    public function listRepresentatives(Request $request)
    {
        $supplier = $this->getAuthenticatedSupplier();
        if (!$supplier) {
            return response()->json(['message' => 'Only authenticated suppliers can access this endpoint'], 403);
        }

        if (!($supplier instanceof Supplier)) {
            return response()->json(['message' => 'Invalid supplier account'], 403);
        }

        return $this->supplierService->listRepresentatives($supplier, $request->all());
    }

    public function listSuborders(Request $request): JsonResponse
    {
        $supplier = $this->getAuthenticatedSupplier();
        if (!$supplier) {
            return response()->json(['message' => 'Only Supplier can access this endpoint'], 403);
        }
        try {
            return $this->orderService->listSubOrders(
                $supplier->id,
                $request->get('search_key'),
                $request->get('data_filter'),
                $request->all(),
                $request->get('status'),
                'supplier',
                $request->get('type_filter'),
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch suborders',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function registerRepresentative(RegisterRepresentativeRequest $request, Supplier $supplier): JsonResponse
    {
        $authenticatedSupplier = $this->getAuthenticatedSupplier();
        if (!$authenticatedSupplier || $authenticatedSupplier->id !== $supplier->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->supplierService->registerRepresentative($supplier, $request->validated());
    }

    public function showRepresentative(Representative $representative): JsonResponse
    {
        $authenticatedSupplier = $this->getAuthenticatedSupplier();
        if (!$authenticatedSupplier || $authenticatedSupplier->id !== $representative->supplier_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->supplierService->getRepresentativeDetails($representative);
    }

    public function updateRepresentative(UpdateRepresentativeRequest $request, Supplier $supplier, Representative $representative): JsonResponse
    {
        $authenticatedSupplier = $this->getAuthenticatedSupplier();
        if (!$authenticatedSupplier || $authenticatedSupplier->id !== $representative->supplier_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->supplierService->updateRepresentative($representative, $request->validated());
    }

    public function destroyRepresentative(Supplier $supplier, Representative $representative): JsonResponse
    {
        $authenticatedSupplier = $this->getAuthenticatedSupplier();
        if (!$authenticatedSupplier || $authenticatedSupplier->id !== $supplier->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->supplierService->deleteRepresentative($supplier, $representative);
    }

    // public function updateRepresentativeProfile(Request $request, Representative $representative): JsonResponse
    // {
    //     $authenticatedSupplier = $this->getAuthenticatedSupplier();
    //     if (!$authenticatedSupplier || $authenticatedSupplier->id !== $representative->supplier_id) {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     $authenticatedSupplier = $this->getAuthenticatedSupplier();
    //     $request->validate([
    //         'name' => 'sometimes|string|max:255',
    //         'email' => 'sometimes|email|unique:suppliers,email,'.$authenticatedSupplier->id,
    //         'password' => 'sometimes|string|min:8'
    //     ]);

    //     $updates = [];
    //     if ($request->has('name')) {
    //         $updates['name'] = $request->name;
    //     }
    //     if ($request->has('password')) {
    //         $updates['password'] = bcrypt($request->password);
    //     }

    //     $representative->update($updates);

    //     return response()->json([
    //         'message' => 'Representative profile updated successfully',
    //         'data' => $representative->fresh()
    //     ]);
    // }

    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|exists:suppliers,phone',
            'verification_code' => 'required|string'
        ]);

        $supplier = Supplier::where('phone', $request->phone)->firstOrFail();
        return $this->supplierService->verifySupplier($supplier, $request->verification_code);
    }

    public function resendVerification(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|exists:suppliers,phone'
        ]);

        $supplier = Supplier::where('phone', $request->phone)->firstOrFail();
        return $this->supplierService->resendVerification($supplier);
    }

    public function checkVerificationStatus(): JsonResponse
    {
        $supplier = $this->getAuthenticatedSupplier();
        if (!$supplier) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $this->supplierService->checkVerificationStatus($supplier);
    }

    private function getAuthenticatedSupplier()
    {
        $supplier = auth('supplier')->user();
        if ($supplier) {
            return $supplier;
        }

        $user = auth('sanctum')->user();
        if (!$user || !$user->role || $user->role->slug !== 'supplier') {
            return null;
        }

        return Supplier::where('user_id', $user->id)->first();
    }
}
