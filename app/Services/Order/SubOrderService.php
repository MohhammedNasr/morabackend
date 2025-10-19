<?php

namespace App\Services\Order;

use App\Http\Resources\SubOrderResource;
use App\Http\Resources\SubOrderDetailResource;
use App\Http\Resources\GroupedSubOrderResource;
use App\Models\SubOrder;
use App\Models\OrderItem;
use App\Models\RejectionReason;
use App\Traits\ApiResponse;
use App\Models\SubOrderTimeline;
use App\Services\BaseService;
use App\Services\Notification\FirebaseNotificationService;
use App\Jobs\SendOrderStatusNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SubOrderService extends BaseService
{
    use ApiResponse;

    public function __construct(
        private FirebaseNotificationService $notificationService
    ) {}

    public function changeSubOrderStatus(SubOrder $subOrder, string $status, ?int $rejection_id = null): JsonResponse
    {
        try {

            $user = request()->user();
            $userRole = $user->role->slug;

            // Verify suborder belongs to user's store/supplier
            if ($userRole === 'store-owner' && $subOrder->order->store->owner_id !== $user->id) {
                throw ValidationException::withMessages([
                    'suborder' => ['Suborder does not belong to your store'],
                ]);
            } elseif ($userRole === 'supplier' && $subOrder->supplier_id !== $user->id) {
                throw ValidationException::withMessages([
                    'suborder' => ['Suborder does not belong to your supplier account'],
                ]);
            }

            $allowedStatuses = SubOrder::$statusValues;
            if ($userRole === 'supplier') {
                $allowedStatuses = ['pending', 'acceptedBySupplier', 'rejectedBySupplier', 'assignToRep', 'modifiedBySupplier'];
            } elseif ($userRole === 'representative') {
                $allowedStatuses = ['pending', 'acceptedByRep', 'rejectedByRep', 'modifiedByRep', 'outForDelivery', 'delivered'];
            }



            if (!in_array($status, $allowedStatuses)) {
                return $this->errorResponse(
                    'Invalid status value for this role',
                    422,
                    ['status' => ['Invalid status value for this role']]
                );
            }

            if ($status === SubOrder::STATUS_REJECTED_BY_REP || $status === SubOrder::STATUS_REJECTED_BY_SUPPLIER) {
                if (empty($rejection_id)) {
                    throw ValidationException::withMessages([
                        'rejection_id' => ['Rejection reason ID is required for rejected status'],
                    ]);
                }

                $reason = RejectionReason::where('id', $rejection_id)
                    ->where('type', $userRole)
                    ->first();

                if (!$reason) {
                    throw ValidationException::withMessages([
                        'rejection_id' => ['Invalid rejection reason for this role'],
                    ]);
                }
            }

            DB::transaction(function () use ($subOrder, $status, $rejection_id, $userRole) {
                $updateData = ['status' => $status];
                if ($status === SubOrder::STATUS_REJECTED_BY_REP || $status === SubOrder::STATUS_REJECTED_BY_SUPPLIER) {
                    $reason = RejectionReason::find($rejection_id);
                    $updateData['rejection_id'] = $rejection_id;
                    $updateData['rejection_reason'] = $reason->reason_en;
                    // $updateData['rejection_type'] = $userRole;
                }
                $subOrder->update($updateData);

                $eventMap = [
                    'pending' => 'Sub Order Pending',
                    'acceptedBySupplier' => 'Sub Order Accepted by Supplier',
                    'rejectedBySupplier' => 'Sub Order Rejected by Supplier',
                    'assignToRep' => 'Sub Order Assigned to Representative',
                    'rejectedByRep' => 'Sub Order Rejected by Representative',
                    'acceptedByRep' => 'Sub Order Accepted by Representative',
                    'modifiedBySupplier' => 'Sub Order Modified by Supplier',
                    'modifiedByRep' => 'Sub Order Modified by Representative',
                    'outForDelivery' => 'Sub Order Out for Delivery',
                    'delivered' => 'Sub Order Delivered'
                ];


                $user = request()->user();
                SubOrderTimeline::create([
                    'sub_order_id' => $subOrder->id,
                    'status' => $status,

                    'rejection_id' => ($status === SubOrder::STATUS_REJECTED_BY_REP || $status === SubOrder::STATUS_REJECTED_BY_SUPPLIER) ? $rejection_id : null,
                    'notes' => 'Sub order status changed to ' . $status .
                        (($status === SubOrder::STATUS_REJECTED_BY_REP || $status === SubOrder::STATUS_REJECTED_BY_SUPPLIER) ? " with reason: " . RejectionReason::find($rejection_id)->reason_en : ''),

                    'user_type' => $user->role->slug,
                    'user_id' => $user->id
                ]);
            });

            $subOrder->refresh();

            // Queue notifications for all contributors
            $notifications = [];
            $title = "Order Status Updated";
            $body = "Order #{$subOrder->reference_number} status changed to {$status}";
            $data = [
                'type' => 'order_status_changed',
                'status' => $status,
                'order_id' => $subOrder->order_id, // Parent order ID for store-owner
                'sub_order_id' => $subOrder->id, // Suborder ID for suppliers/representatives
                'user_type' => $user->role->slug,

            ];

            if ($subOrder->supplier) {

                $notifications[] = new SendOrderStatusNotification(
                    $subOrder->supplier->id,
                    'supplier',
                    $title,
                    $body,
                    $data
                );
            }

            if ($subOrder->representative) {
                $notifications[] = new SendOrderStatusNotification(
                    $subOrder->representative->id,
                    'representative',
                    $title,
                    $body,
                    $data
                );
            }


            if (
                $subOrder->order && $subOrder->order->store && $subOrder->order->store->owner
                && ($subOrder->status == SubOrder::STATUS_ACCEPTED_BY_SUPPLIER
                    || $subOrder->status == SubOrder::STATUS_DELIVERED || SubOrder::STATUS_MODIFIED_BY_REP
                    || SubOrder::STATUS_MODIFIED_BY_SUPPLIER || SubOrder::STATUS_REJECTED_BY_SUPPLIER
                )
            ) {
                $notifications[] = new SendOrderStatusNotification(
                    $subOrder->order->store->owner_id,
                    'user',
                    $title,
                    $body,
                    $data
                );
            }

            // Batch dispatch all notifications
            if (!empty($notifications)) {
                Log::info('Dispatching batch notifications', [
                    'count' => count($notifications),
                    'recipients' => array_map(function ($n) {
                        return $n->userId ?? 'unknown';
                    }, $notifications)
                ]);
                $batch = Bus::batch($notifications)->dispatch();
                Log::info('Batch dispatched', ['batch_id' => $batch->id]);
            } else {
                Log::info('No notifications to dispatch');
            }

            return $this->successResponse(
                data: ['sub_order' => new SubOrderDetailResource($subOrder)],
                message: 'Sub order status changed successfully',
                hint: 'The sub order status has been updated to ' . $status
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getSubOrderDetails(SubOrder $subOrder): JsonResponse
    {
        try {
            $subOrder->load([
                'order.store',
                'order.storeBranch',
                'supplier',
                'representative',
                'items.product.category',
                'timelines'
            ]);

            return $this->successResponse(
                data: [
                    'sub_order' => new SubOrderDetailResource($subOrder)
                ],
                message: 'Sub-order details retrieved successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function modifySubOrder(array $data, $subOrder): JsonResponse
    {
        if (!$subOrder instanceof SubOrder) {
            $subOrder = SubOrder::findOrFail((int)$subOrder);
        }

        try {
            DB::transaction(function () use ($data, $subOrder) {
                foreach ($data['items'] as $item) {
                    $orderItem = OrderItem::find($item['id']);

                    if ($item['action'] === 'update') {
                        $quantityDiff = $item['quantity'] - $orderItem->quantity;
                        $priceDiff = $quantityDiff * $orderItem->unit_price;

                        $orderItem->update([
                            'previous_quantity' => $orderItem->quantity,
                            'previous_unit_price' => $orderItem->unit_price,
                            'previous_total_price' => $orderItem->total_price,
                            'is_modified' => 1,
                            'quantity' => $item['quantity'],
                            'total_price' => $item['quantity'] * $orderItem->unit_price,
                            'modification_notes' => $item['notes'] ?? null
                        ]);

                        $subOrder->update([
                            'is_modified' => 1,
                            'previous_amount' => $subOrder->total_amount,
                            'products_count' => $subOrder->products_count + $quantityDiff,
                            'total_amount' => $subOrder->total_amount + $priceDiff,
                            'sub_total' => $subOrder->sub_total + $priceDiff,
                            'modification_notes' => $data['notes'] ?? null
                        ]);
                    } elseif ($item['action'] === 'delete') {
                        $subOrder->update([
                            'is_modified' => 1,
                            'products_count' => $subOrder->products_count - $orderItem->quantity,
                            'total_amount' => $subOrder->total_amount - $orderItem->total_price,
                            'sub_total' => $subOrder->sub_total - $orderItem->total_price,
                            'modification_notes' => $data['notes'] ?? null
                        ]);
                        $orderItem->update([
                            'is_available' => false,
                            'is_modified' => true,
                            'previous_quantity' => $orderItem->quantity,
                            'previous_unit_price' => $orderItem->unit_price,
                            'previous_total_price' => $orderItem->total_price,
                            'quantity' => 0,
                            'modification_notes' => $item['notes'] ?? "Item removed from order"
                        ]);
                    }
                }

                SubOrderTimeline::create([
                    'sub_order_id' => $subOrder->id,
                    'status' => (request()->user()->role->name == 'supplier') ? 'modifiedBySupplier' : 'modifiedByRep',
                    'notes' => 'Sub order items were modified',
                    'user_id' => request()->user()->id,
                    'user_type' => request()->user()->role->name
                ]);

                $subOrder->load(['items.product', 'timelines']);
            });

            return $this->successResponse(
                data: ['sub_order' => new SubOrderDetailResource($subOrder)],
                message: 'Sub order modified successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function listSubOrders($owner_id, $searchKey = null, $dateFilter = null, $data, $status = null, $type = 'representative', $typeFilter = null)
    {
        $query = SubOrder::query()->verifiedOrRequiresMoraApproval();
        if ($type == 'representative')
            $query = $query->where('representative_id', $owner_id);
        else
            $query = $query->where('supplier_id', $owner_id);

        if (isset($typeFilter)) {
            if ($type == 'supplier') {
                if ($typeFilter === 'new') {
                    $query->whereIn('status', [SubOrder::STATUS_PENDING, SubOrder::STATUS_ACCEPTED_BY_SUPPLIER, SubOrder::STATUS_MODIFIED_BY_SUPPLIER]);
                } elseif ($typeFilter === 'assigned') {
                    $query->whereIn('status', [SubOrder::STATUS_ASSIGNED_TO_REP, SubOrder::STATUS_REJECTED_BY_REP, SubOrder::STATUS_ACCEPTED_BY_REP, SubOrder::STATUS_MODIFIED_BY_REP, SubOrder::STATUS_OUT_FOR_DELIVERY, SubOrder::STATUS_DELIVERED]);
                }
            } else {
                // For representative
                if ($typeFilter === 'new') {
                    $query->where('status', SubOrder::STATUS_ASSIGNED_TO_REP);
                } elseif ($typeFilter === 'under_processing') {
                    $query->whereIn('status', [SubOrder::STATUS_ACCEPTED_BY_REP, SubOrder::STATUS_MODIFIED_BY_REP]);
                }
            }
        }

        if ($status) {
            if ($type == 'supplier') {
                // Supplier filtering
                if ($status == "under_processing") {
                    $query->whereIn('status', [SubOrder::STATUS_ACCEPTED_BY_SUPPLIER, SubOrder::STATUS_MODIFIED_BY_SUPPLIER, SubOrder::STATUS_ASSIGNED_TO_REP, SubOrder::STATUS_ACCEPTED_BY_REP, SubOrder::STATUS_MODIFIED_BY_REP]);
                } else {
                    $query->where('status', $status);
                }
            } else {
                // Representative filtering
                if ($status == "delivered") {
                    $query->where('status', SubOrder::STATUS_DELIVERED);
                } elseif ($status == "outForDelivery") {
                    $query->where('status', SubOrder::STATUS_OUT_FOR_DELIVERY);
                } elseif ($status == "new") {
                    $query->where('status', SubOrder::STATUS_ASSIGNED_TO_REP);
                } elseif ($status == "under_processing") {
                    $query->whereIn('status', [SubOrder::STATUS_ACCEPTED_BY_REP, SubOrder::STATUS_MODIFIED_BY_REP]);
                }
            }
        }


        if ($dateFilter === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($dateFilter === 'thisWeek') {
            $query->whereBetween('created_at', [
                now()->startOfWeek()->subDay(2), // Start from Saturday (default is Sunday, so subtract 1 day)
                now()->endOfWeek()
            ]);
        } elseif ($dateFilter === 'from-to') {
            $fromDate = request()->input('from_date');
            $toDate = request()->input('to_date');
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        if ($searchKey) {
            $query->where(function ($q) use ($searchKey) {
                $q->where('reference_number', 'like', "%{$searchKey}%")
                    ->orWhereHas('representative', function ($q) use ($searchKey) {
                        $q->where('name', 'like', "%{$searchKey}%");
                    });
            });
        }

// First get filtered results without grouping
$filteredQuery = clone $query;
$filteredResults = $filteredQuery->orderBy('id', 'desc')->get();
$totalOrders = $filteredResults->count();

// Then group the filtered results
if ($type == 'supplier') {
    $groupedResults = $filteredResults->groupBy(function ($item) {
        return $item->supplier_id . '|' . $item->created_at->format('Y-m-d');
    })->map(function ($group) {
        return (object)[
            'supplier_id' => $group->first()->supplier_id,
            'order_date' => $group->first()->created_at->format('Y-m-d'),
            'total_orders' => $group->count(),
            'orders' => $group->values(), // Pass the orders to the resource
        ];
    })->values();
} else {
    $groupedResults = $filteredResults->groupBy(function ($item) {
        return $item->representative_id . '|' . $item->created_at->format('Y-m-d');
    })->map(function ($group) {
        return (object)[
            'representative_id' => $group->first()->representative_id,
            'order_date' => $group->first()->created_at->format('Y-m-d'),
            'total_orders' => $group->count(),
            'orders' => $group->values(), // Pass the orders to the resource
        ];
    })->values();
}

// Manually paginate the grouped results
$page = $data['page'] ?? 1;
$perPage = $data['per_page'] ?? 15;
$offset = ($page - 1) * $perPage;
$paginatedResults = $groupedResults->slice($offset, $perPage)->values();

return $this->successResponse(
    data: [
        'order_group' => GroupedSubOrderResource::collection($paginatedResults->map(function ($group) {
            return (object)[
                'order_date' => $group->order_date,
                'total_orders' => $group->total_orders,
                'representative_id' => $group->representative_id ?? null,
                'supplier_id' => $group->supplier_id ?? null,
                'orders' => $group->orders, // Pass the orders to the resource
            ];
        })),
        'pagination' => [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $totalOrders,
            'last_page' => ceil($totalOrders / $perPage),
        ],
    ],
    message: 'Orders Retrieved successfully',
    hint: "Orders Retrieved successfully, only verified orders will be returned"
);
    }
}
