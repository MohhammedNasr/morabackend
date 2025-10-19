<?php

namespace App\Http\Resources;

use App\Http\Requests\Branch\StoreBranchRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderItem;
use App\Models\StoreBranch;
use App\Models\SubOrder;
use App\Http\Resources\OrderPaymentResource;

class OrderDetailResource extends JsonResource
{
    protected function getSimplifiedStatus(string $status): string
    {
        return match ($status) {
            SubOrder::STATUS_PENDING => 'pending',
            SubOrder::STATUS_ACCEPTED_BY_SUPPLIER => 'under_processing',
            SubOrder::STATUS_ASSIGNED_TO_REP => 'under_processing',
            SubOrder::STATUS_ACCEPTED_BY_REP => 'under_processing',
            SubOrder::STATUS_MODIFIED_BY_SUPPLIER => 'modified',
            SubOrder::STATUS_MODIFIED_BY_REP => 'modified',
            SubOrder::STATUS_REJECTED_BY_SUPPLIER => 'rejected',
            SubOrder::STATUS_REJECTED_BY_REP => 'under_processing',
            SubOrder::STATUS_OUT_FOR_DELIVERY => 'out_for_delivery',
            SubOrder::STATUS_DELIVERED => 'delivered'
        };
    }

    protected function getCompletionPercentage(): float
    {
        if (!$this->subOrders) {
            return 0;
        }

        $total = $this->subOrders->count();
        $completed = $this->subOrders->whereIn('status', [SubOrder::STATUS_DELIVERED, SubOrder::STATUS_REJECTED_BY_SUPPLIER])->count();

        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_modified' => $this->is_modified,
            'completion_percentage' => $this->getCompletionPercentage(),
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'total_amount' => $this->total_amount,
            'sub_total' => $this->sub_total ?? 0,
            'discount' => $this->discount ?? 0,
            'previous_sub_total' => $this->previous_sub_total,
            'previous_total_amount' => $this->previous_total_amount,
            'products_count' => $this->products_count,
            'categories_count' => $this->categories_count,
            'suppliers_count' => $this->suppliers_count,
            'branch' => new StoreBranchResource($this->storeBranch),
            // [
            //     'name' => $this->storeBranch->name,
            //     'address' => $this->storeBranch->address,
            //     'phone' => $this->storeBranch->phone,
            // ],
            'items' => $this->items()->with('product')->get()->map(function ($item) {
                if ($item->is_available)
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->getName(),
                            'image' => asset($item->product->image),
                            'sku' => $item->product->sku,
                        ],
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                    ];
            }),
            'suppliers' => $this->whenLoaded('subOrders', function () {
                return $this->subOrders->load(['items' => function ($query) {
                    $query->with('product');
                }, 'supplier'])->map(function ($subOrder) {
                    $items = OrderItem::where([
                        'sub_order_id' => $subOrder->id,
                        'supplier_id' => $subOrder->supplier->id,

                    ])->get();

                    return [
                        'id' => $subOrder->supplier->id,
                        'sub_order_id' => $subOrder->id,
                        'reference_number' => $subOrder->reference_number ?? null,
                        'name' => $subOrder->supplier->name,
                        'products_count' => $subOrder->products_count,
                        'status' => $this->getSimplifiedStatus($subOrder->status),
                        'total_amount' => $subOrder->total_amount,
                        'previous_total_amount' => $subOrder->previous_amount,
                        'rejection_reason' => $subOrder->rejection_reason ?? null,
                        'items' => $items->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'product' => [
                                    'id' => $item->product->id,
                                    'name' => $item->product->getName(),
                                    'category_name' => $item->product->category->{'name_' . app()->getLocale()},
                                    'image' => asset($item->product->image),
                                    'sku' => $item->product->sku,
                                ],
                                'previous_total_price' => $this->previous_total_price,
                                'quantity' => $item->quantity,
                                'unit_price' => $item->unit_price,
                                'total_price' => $item->total_price,
                            ];
                        })
                    ];
                });
            }),

            'modified_suppliers' => $this->whenLoaded('subOrders', function () {
                return $this->subOrders->load(['items' => function ($query) {
                    $query->with('product')->where('is_modified', 1);
                }, 'supplier'])->map(function ($subOrder) {
                    $modified_items = OrderItem::where([
                        'sub_order_id' => $subOrder->id,
                        'supplier_id' => $subOrder->supplier->id,
                        'is_modified' => 1
                    ])->get();

                    return [
                        'id' => $subOrder->supplier->id,
                        'sub_order_id' => $subOrder->id,
                        'reference_number' => $subOrder->reference_number ?? null,
                        'name' => $subOrder->supplier->name,
                        'products_count' => $subOrder->products_count,
                        'status' => $this->getSimplifiedStatus($subOrder->status),
                        'total_amount' => $subOrder->total_amount,
                        'previous_total_amount' => $subOrder->previous_amount,
                        'rejections_reason' => $subOrder->rejection_reason ?? null,
                        'items' => $modified_items->map(function ($item) {
                            return new ModifiedSupplierItemResource($item);
                        })
                    ];
                });
            }),
            'payments' => $this->whenLoaded('payments', fn() => OrderPaymentResource::collection($this->payments)),
            'created_at' => $this->created_at,
            'payment_due_date' => $this->payment_due_date,
            'requires_mora_approval' => $this->requires_mora_approval,
        ];
    }
}
