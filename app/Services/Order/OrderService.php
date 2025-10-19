<?php

namespace App\Services\Order;

use App\Services\BaseService;
use App\Services\Promotion\PromotionService;
use App\Models\Order;
use App\Models\OrderTimeline;
use App\Models\Product;
use App\Models\SubOrder;

use App\Models\OrderItem;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderDetailResource;
use App\Models\Store;
use App\Models\StoreBranch;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderService extends BaseService
{
    protected PromotionService $promotionService;

    public function __construct(PromotionService $promotionService)
    {
        $this->promotionService = $promotionService;
    }

    /**
     * Get orders list with filters
     */
    public function getOrders(array $data, $user): JsonResponse
    {

        try {
            $query = Order::query()
                ->when($user->isStoreOwner(), function ($query) use ($user) {
                    $query->whereHas('store', function ($q) use ($user) {
                        $q->where('owner_id', $user->id);
                    });
                })
                //->orWhere("user_id", $user->id)
                ->when(isset($data['type']) && $data['type'] === 'upcoming', function ($query) {
                    $query->upcoming();
                })
                ->when(isset($data['type']) && $data['type'] === 'historical', function ($query) {
                    $query->historical();
                })
                ->when(isset($data['status']), function ($query, $status) use ($data) {
                    if ($data['status'] == 'under_processing') {
                        $query->whereIn('status', ['under_processing', 'verified']);
                    } else {
                        $query->where('status', $data['status']);
                    }
                })
                ->when(isset($data['from_date']) && isset($data['to_date']), function ($query) use ($data) {
                    $query->whereBetween('created_at', [
                        $data['from_date'],
                        $data['to_date']
                    ]);
                })
                ->when(isset($data['order_number']), function ($query) use ($data) {
                    $query->where('reference_number', 'like', '%' . $data['order_number'] . '%');
                })
                ->with(['store', 'items.product', 'subOrders.supplier'])
                ->latest();

            $orders = $this->paginate($query, $data);

            return $this->successResponse(
                data: [
                    'orders' => OrderResource::collection($orders),
                    'pagination' => [
                        'current_page' => $orders->currentPage(),
                        'per_page' => $orders->perPage(),
                        'total' => $orders->total(),
                        'last_page' => $orders->lastPage(),
                    ],
                ],
                message: __('api.success')
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Create a new order
     */
    public function createOrder(array $data, $user): JsonResponse
    {
        try {
            // Get primary store for user
            if (!isset($data['store_id'])) {
                if ($this->getUserType() == 'store-owner') {
                    $store = Store::where('owner_id', $user->id)->first();
                    if (!$store) {
                        throw ValidationException::withMessages([
                            'store' => [__('api.not_found', ['attribute' => 'store'])],
                        ]);
                    }
                } else {
                    $storeBranch = StoreBranch::with('store')->where('id',  $data['store_branch_id'])->first();
                    $store = $storeBranch->store;
                }
            } else {
                $store = Store::find($data['store_id']);
            }


            if (!$store->is_verified) {
                throw ValidationException::withMessages([
                    'store' => [__('api.unverified', ['attribute' => 'store'])],
                ]);
            }

            return DB::transaction(function () use ($data, $store, $user) {
                // Create order first
                $status = $store->auto_verify_order ? Order::STATUS_VERIFIED : Order::STATUS_PENDING;

                $order = Order::create([
                    'store_id' => $store->id,
                    'store_branch_id' => $data['store_branch_id'],
                    //  'reference_number' => 'ORD-' . strtoupper(Str::random(8)),
                    'status' => $status,
                    'sub_total' => 0, // Will be updated after calculating items
                    'total_amount' => 0, // Will be updated after calculating items and discounts
                    'payment_due_date' =>  now()->addDays(60), // Will be set after getting supplier info
                    'requires_mora_approval' => false, // Will be updated after calculating total
                    'verification_code' => random_int(0, 9999),
                    'user_id' => $user->id,
                    'user_type' => $user->role->slug
                ]);

                $totalAmount = 0;
                $items = [];
                $productsCount = 0;
                $supplierItems = [];

                foreach ($data['items'] as $item) {
                    $product = Product::with('supplier')->findOrFail($item['product_id']);
                    $supplier = $product->supplier;

                    if (!$supplier) {
                        throw ValidationException::withMessages([
                            'product' => [__('api.invalid_supplier')],
                        ]);
                    }

                    $totalPrice = $product->price * $item['quantity'];
                    $totalAmount += $totalPrice;
                    $productsCount += $item['quantity'];

                    // Group items by supplier
                    $supplierItems[$supplier->id][] = [
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'total_price' => $totalPrice,
                        'supplier_id' => $supplier->id
                    ];

                    $items[] = [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $product->price,
                        'total_price' => $totalPrice,
                        'supplier_id' => $supplier->id,
                        'sub_order_id' => null // Will be updated when creating sub-orders
                    ];
                }

                if (!$store->hasAvailableCredit($totalAmount)) {
                    throw ValidationException::withMessages([
                        'credit_limit' => [__('api.insufficient_credit')],
                    ]);
                }

                // Update order with calculated totals
                $order->update([
                    'sub_total' => $totalAmount,
                    'total_amount' => $totalAmount,
                    'payment_due_date' => now()->addDays(60),
                    'requires_mora_approval' => $totalAmount > 50000,
                ]);
                $promoResult = null;

                // Apply promo code if provided
                if (!empty($data['promo_code'])) {
                    $promoResult = $order->applyPromoCode($data['promo_code'], $this->promotionService);
                    if (isset($promoResult['success']) && $promoResult['success']) {
                        // $order->update([
                        //     'promotion_id' => $promoResult['promotion_id'],
                        //     'discount' => $promoResult['discount'],
                        //     'sub_total' => $order->total_amount,
                        //     'total_amount' => $order->total_amount - $promoResult['discount']
                        // ]);
                    } else {
                        // Update order with promotion details
                        throw ValidationException::withMessages([
                            'promo_code' => [$promoResult['message'] ?? __('api.invalid_promo')],
                        ]);
                    }



                    // Refresh total amount after applying promo code
                    $totalAmount = $order->total_amount;
                }

                // Create order items
                $order->items()->createMany($items);

                // Create sub orders for each supplier
                foreach ($supplierItems as $supplierId => $supplierProducts) {
                    $subOrderTotal = array_sum(array_column($supplierProducts, 'total_price'));
                    $subOrderCount = array_sum(array_column($supplierProducts, 'quantity'));

                    $subOrder = SubOrder::create([
                        'order_id' => $order->id,
                        'supplier_id' => $supplierId,
                        'status' => SubOrder::STATUS_PENDING,
                        'total_amount' => $subOrderTotal,
                        'products_count' => $subOrderCount,
                        'sub_total' => $subOrderTotal,
                        'discount' => $order->discount ? ($subOrderTotal / $totalAmount) * $order->discount : 0,
                        'promotion_id' => $order->promotion_id,
                    ]);

                    // Create sub order items
                    // Update order items with sub_order_id
                    foreach ($supplierProducts as $product) {
                        OrderItem::where('order_id', $order->id)
                            ->where('product_id', $product['product_id'])
                            ->update(['sub_order_id' => $subOrder->id]);
                    }
                }



                // Create payment records for 4 phases
                $paymentAmount = $order->total_amount / 4;
                $paymentDates = [
                    now()->addDays(15),
                    now()->addDays(30),
                    now()->addDays(45),
                    now()->addDays(60),
                ];

                foreach ($paymentDates as $index => $date) {
                    $order->payments()->create([
                        'amount' => $paymentAmount,
                        'due_date' => $date,
                        'status' => 'pending',
                        'payment_method' => null,
                        'transaction_number' => null,
                        'notes' => 'payment_phase_' . ($index + 1),
                    ]);
                }
                $order->load([
                    'storeBranch',
                    'subOrders.supplier',
                    'subOrders.items',
                    'subOrders.items.product',
                    'payments'
                ]);

                return $this->successResponse(
                    data: [
                        'order' => new OrderDetailResource($order),
                        'verification_code' => $store->auto_verify_order ? null : $order->verification_code
                    ],
                    hint: $store->auto_verify_order
                        ? __('api.order_created_verified')
                        : __('api.order_created_verify'),
                    message: $store->auto_verify_order
                        ? __('api.order_created_verified')
                        : __('api.order_created_verify'),
                    statusCode: 201
                );
            });
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Get order details with all relationships
     */
    public function getOrderDetails(Order $order): JsonResponse
    {
        try {
            $order->load([
                'storeBranch',
                'items.product',
                'subOrders.supplier',
                'subOrders.items.product',
                'payments'
            ]);

            return $this->successResponse(
                data: ['order' => new OrderDetailResource($order)],
                message: 'Order details retrieved successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Verify an order
     */
    public function verifyOrder(array $data, Order $order): JsonResponse
    {

        try {
            if (!$order->isVerificationCodeValid($data['verification_code'])) {

                // Generate and send new verification code
                $newCode = Order::generateVerificationCode();
                $order->update([
                    'verification_code' => $newCode
                ]);
                //$order->sendVerificationCode();

                throw ValidationException::withMessages([
                    'verification_code' => ['Invalid code. A new verification code has been sent.', 'code' => $newCode],
                ]);
            }

            DB::transaction(function () use ($order, $data) {
                $order->update([
                    'status' => Order::STATUS_VERIFIED,
                    'verified_at' => now()
                ]);

                OrderTimeline::create([
                    'order_id' => $order->id,
                    'event' => 'Order Verified',
                    'description' => 'Order verified with code ' . $data['verification_code'],
                    'created_by' => request()->user()->id,
                    'user_id' => request()->user()->id,
                    'user_type' => $this->getUserType()
                ]);
            });

            return $this->successResponse(
                data: [
                    'order' => new OrderDetailResource($order)
                ],
                message: 'Order verified successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Cancel an order
     */
    public function resendVerificationCode(Order $order): JsonResponse
    {
        try {
            if ($order->status !== Order::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'order' => ['Verification code can only be resent for pending orders'],
                ]);
            }

            $newCode = Order::generateVerificationCode();
            $order->update([
                'verification_code' => $newCode
            ]);

            $this->sendSms(request()->user()->phone, 'Your verification code has been resent: ' . $newCode);

            OrderTimeline::create([
                'order_id' => $order->id,
                'event' => 'Verification Code Resent',
                'description' => 'New verification code sent',
                'created_by' => request()->user()->id,
                'user_id' => request()->user()->id,
                'user_type' => $this->getUserType()
            ]);

            return $this->successResponse(
                data: [
                    'order' => new OrderDetailResource($order),
                    'verification_code' => app()->environment('local') ? $newCode : null
                ],
                message: 'New verification code sent successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function cancelOrder(array $data, Order $order): JsonResponse
    {
        try {
            // Prevent canceling an already canceled order
            if ($order->status === Order::STATUS_CANCELED) {
                throw ValidationException::withMessages([
                    'order' => ['Order is already canceled'],
                ]);
            }

            DB::transaction(function () use ($order, $data) {
                $order->update(['status' => Order::STATUS_CANCELED]);

                OrderTimeline::create([
                    'order_id' => $order->id,
                    'event' => 'Order Cancelled',
                    'description' => 'Order cancelled: ' . ($data['reason'] ?? 'No reason provided'),
                    'created_by' => request()->user()->id,
                    'user_id' => request()->user()->id,
                    'user_type' => $this->getUserType()
                ]);
            });

            return $this->successResponse(
                data: ['order' => new OrderDetailResource($order)],
                message: 'Order cancelled successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Change order status
     */
    public function changeOrderStatus(Order $order, string $status, $user): JsonResponse
    {
        try {
            // Prevent status changes for canceled orders
            if ($order->status === Order::STATUS_CANCELED) {
                throw ValidationException::withMessages([
                    'status' => ['Cannot change status of a canceled order'],
                ]);
            }

            // Validate status transition
            $allowedStatuses = [
                Order::STATUS_UNDER_PROCESSING,
                Order::STATUS_COMPLETED,
                Order::STATUS_VERIFIED,
            ];

            if (!in_array($status, $allowedStatuses)) {
                throw ValidationException::withMessages([
                    'status' => ['Invalid status transition'],
                ]);
            }

            DB::transaction(function () use ($order, $status, $user) {
                $order->update(['status' => $status]);

                $eventMap = [
                    Order::STATUS_UNDER_PROCESSING => 'Order Under Processing',
                    Order::STATUS_COMPLETED => 'Order Completed',
                    Order::STATUS_VERIFIED => 'Order Verified',
                ];

                OrderTimeline::create([
                    'order_id' => $order->id,
                    'event' => $eventMap[$status],
                    'description' => 'Order status changed to ' . $status . ' by ' . $user->name,
                    'created_by' => $user->id,
                    'user_id' => $user->id,
                    'user_type' => $this->getUserType()
                ]);
            });

            return $this->successResponse(
                data: ['order' => new OrderDetailResource($order)],
                message: 'Order status changed successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }


    public function modifyOrder(array $data, Order $order, $user): JsonResponse
    {
        try {
            // Verify order belongs to user's store
            if ($user->isStoreOwner() && $order->store->owner_id !== $user->id) {
                throw ValidationException::withMessages([
                    'order' => ['Order does not belong to your store'],
                ]);
            }

            DB::transaction(function () use ($data, $order, $user) {
                // Verify all items exist and belong to this order
                foreach ($data['items'] as $item) {
                    $orderItem = OrderItem::where('id', $item['id'])
                        ->where('order_id', $order->id)
                        ->first();

                    if (!$orderItem) {
                        throw ValidationException::withMessages([
                            'items' => ['One or more order items were not found'],
                        ]);
                    }

                    if ($orderItem->sub_order_id) {
                        $subOrder = SubOrder::where('id', $orderItem->sub_order_id)
                            ->where('order_id', $order->id)
                            ->first();

                        if (!$subOrder) {
                            throw ValidationException::withMessages([
                                'items' => ['One or more sub orders were not found'],
                            ]);
                        }
                    }
                }
                foreach ($data['items'] as $item) {
                    $orderItem = OrderItem::find($item['id']);
                    $subOrder = SubOrder::find($orderItem->sub_order_id);

                    if ($item['action'] === 'update') {
                        // Calculate the difference in quantity and price
                        $quantityDiff = $item['quantity'] - $orderItem->quantity;
                        $priceDiff = $quantityDiff * $orderItem->unit_price;
                        // Update the order item
                        $orderItem->update([
                            'previous_quantity' => $orderItem->quantity,
                            'previous_unit_price' => $orderItem->unit_price,
                            'previous_total_price' => $orderItem->total_price,
                            'is_modified' => 1,
                            'quantity' => $item['quantity'],
                            'total_price' => $item['quantity'] * $orderItem->unit_price,
                            'modification_notes' => "available quantity is " . $item['quantity']
                        ]);
                        // Update the sub order totals
                        $subOrder->update([
                            'is_modified' => 1,
                            'previous_amount' => $subOrder->total_amount,
                            'products_count' => $subOrder->products_count + $quantityDiff,
                            'total_amount' => $subOrder->total_amount + $priceDiff,
                            'sub_total' => $subOrder->sub_total + $priceDiff,
                            'modification_notes' => null
                        ]);
                    } elseif ($item['action'] === 'delete') {
                        // Update sub order totals and mark item as not available
                        $subOrder->update([
                            'is_modified' => 1,
                            'products_count' => $subOrder->products_count - $orderItem->quantity,
                            'total_amount' => $subOrder->total_amount - $orderItem->total_price,
                            'sub_total' => $subOrder->sub_total - $orderItem->total_price
                        ]);
                        $orderItem->update([
                            'is_available' => false,
                            'is_modified' => true,
                            'previous_quantity' => $orderItem->quantity,
                            'previous_unit_price' => $orderItem->unit_price,
                            'previous_total_price' => $orderItem->total_price,
                            'quantity' => 0,
                            'modification_notes' => "Order not available"
                        ]);
                    }
                }

                // Update order with previous amounts and modification details
                $order->update([
                    'previous_sub_total' => $order->sub_total,
                    'previous_total_amount' => $order->total_amount,
                    'is_modified' => true,
                    'modification_notes' =>  null
                ]);

                // Recalculate order totals
                $order->recalculateTotals();

                // Recalculate all sub order totals
                foreach ($order->subOrders as $subOrder) {
                    $subOrder->update([
                        'total_amount' => $subOrder->items()->sum('total_price'),
                        'products_count' => $subOrder->items()->sum('quantity')
                    ]);
                }

                // Update order with new totals and refresh counts
                $order->update([
                    'sub_total' => $order->items()->sum(DB::raw('quantity * unit_price')),
                    'total_amount' => $order->sub_total - ($order->discount ?? 0)
                ]);
                $order->refresh();

                OrderTimeline::create([
                    'order_id' => $order->id,
                    'event' => 'Order Modified',
                    'description' => 'Order items were modified',
                    'created_by' => request()->user()->id,
                    'user_id' => request()->user()->id,
                    'user_type' => $this->getUserType()
                ]);
            });

            return $this->successResponse(
                data: ['order' => new OrderDetailResource($order)],
                message: 'Order modified successfully'
            );
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
