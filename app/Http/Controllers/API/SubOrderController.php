<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubOrder\ChangeSubOrderStatusRequest;
use App\Http\Requests\SubOrder\ModifySubOrderRequest;
use App\Models\Representative;
use App\Models\SubOrder;
use App\Services\Order\SubOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubOrderController extends Controller
{
    public function __construct(
        private SubOrderService $orderService
    ) {}

    /**
     * Display the specified sub-order.
     */
    public function show(SubOrder $subOrder): JsonResponse
    {
        $this->authorize('view', $subOrder);
        return $this->orderService->getSubOrderDetails($subOrder);
    }

    public function modifySubOrder(ModifySubOrderRequest $request, $subOrder): JsonResponse
    {
        //  $this->authorize('modifySubOrder', $subOrder);
        return $this->orderService->modifySubOrder(
            $request->validated(),
            $subOrder
        );
    }

    public function changeStatus(SubOrder $subOrder, ChangeSubOrderStatusRequest $request): JsonResponse
    {
        // $this->authorize('changeStatus', $subOrder);


       return $this->orderService->changeSubOrderStatus(

            $subOrder,
            $request->validated('status'),
            $request->validated('rejection_id')
        );

        // return response()->json([
        //     'message' => 'Sub order status updated successfully',
        //     'data' => new SubOrderDetailResource($subOrder->fresh())
        // ]);
    }

    public function listAssignedOrders(Request $request): JsonResponse
    {
        $this->authorize('viewAny', SubOrder::class);

        $representative = $this->getAuthenticatedRepresentative();
        if (!$representative) {
            return response()->json(['message' => 'Only representatives can access this endpoint'], 403);
        }

        return $this->orderService->listSubOrders(
            $representative->id,
            $request->get('search_key'),
            $request->get('data_filter', 'allOrders'),
            $request->all(),
            $request->get('status'),
            'representative',
            $request->get('type_filter'),

        );
    }

    /**
     * Get the authenticated representative.
     *
     * @return Representative|null Returns the representative if found, or null if not
     */
    private function getAuthenticatedRepresentative(): ?Representative
    {
        // Try to authenticate as a representative directly
        $representative = auth('representative')->user();

        // If not authenticated as a representative, check if authenticated as a user
        if (!$representative) {
            // Try to authenticate with sanctum
            $user = auth('sanctum')->user();

            if (!$user || !$user->role || $user->role->slug !== 'representative') {
                return null;
            }

            // Try to find the representative by user_id if there's a relationship
            $representative = Representative::where('user_id', $user->id)->first();
        }

        return $representative;
    }
}
