<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderItem;

class SubOrderDetailResource extends JsonResource
{
    protected function getCompletionPercentage(): float
    {
        return 0; // Sub-orders don't have a completion percentage like orders do
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'total_amount' => $this->total_amount,
            'sub_total' => $this->sub_total ?? 0,
            'discount' => $this->discount ?? 0,
            'products_count' => $this->products_count,
            'categories_count' => $this->categories_count,
            'is_modified' => $this->is_modified,
            'previous_amount' => $this->previous_amount,
            'promotion_id' => $this->promotion_id,
            'rejection_reason' => $this->rejection_reason ?? null,

            // Order information
            'order' => [
                'id' => $this->order->id,
                'reference_number' => $this->order->reference_number,
                'status' => $this->order->status,
                'created_at' => $this->order->created_at,
            ],

            // Store information
            'store' => new StoreResource($this->order->store),

            // Branch information
            'branch' => $this->when($this->order->storeBranch, function () {
                return new StoreBranchResource($this->order->storeBranch);
            }),

            // Supplier information
            'items' => $this->items()->with(['product.category'])->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->getName(),
                        'category_name' => $item->product->category->{'name_' . app()->getLocale()} ?? null,
                        'image' => asset($item->product->image),
                        'sku' => $item->product->sku,
                    ],
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'previous_total_price' => $item->previous_total_price,
                ];
            }),
            // 'supplier' => [
            //     'id' => $this->supplier->id,
            //     'sub_order_id' => $this->id,
            //     'name' => $this->supplier->name,
            //     'contact_name' => $this->supplier->contact_name,
            //     'email' => $this->supplier->email,
            //     'phone' => $this->supplier->phone,
            //     'address' => $this->supplier->address,
            //     'products_count' => $this->products_count,
            //     'status' => $this->status,
            //     'total_amount' => $this->total_amount,
            //     'previous_total_amount' => $this->previous_amount,
            //     'rejection_reason' => $this->rejection_reason ?? null,

            // ],

            // Modified supplier information
            'modified_items' => $this->items()->with(['product.category'])
                ->where('is_modified', 1)
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => [
                            'id' => $item->product->id,
                            'name' => $item->product->getName(),
                            'category_name' => $item->product->category->{'name_' . app()->getLocale()} ?? null,
                            'image' => asset($item->product->image),
                            'sku' => $item->product->sku,
                        ],
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price' => $item->total_price,
                        'previous_total_price' => $item->previous_total_price,
                        'modification_notes' => $item->modification_notes,
                    ];
                }),
            // 'modified_supplier' => [
            //     'id' => $this->supplier->id,
            //     'sub_order_id' => $this->id,
            //     'name' => $this->supplier->name,
            //     'products_count' => $this->products_count,
            //     'status' => $this->status,
            //     'total_amount' => $this->total_amount,
            //     'previous_total_amount' => $this->previous_amount,
            //     'rejection_reason' => $this->rejection_reason ?? null,

            // ],

            // Representative information (if assigned)
            'representative' => $this->when($this->representative, function () {
                return [
                    'id' => $this->representative->id,
                    'name' => $this->representative->name,
                    'phone' => $this->representative->phone,
                    'email' => $this->representative->email,
                ];
            }),

            // Timeline events
            'timeline' => $this->whenLoaded('timelines', function () {
                return $this->timelines->map(function ($timeline) {
                    return [
                        'id' => $timeline->id,
                        'event' => $timeline->event,
                        'description' => $timeline->description,
                        'created_at' => $timeline->created_at,
                        'user' => [
                            'id' => $timeline->user_id,
                            'type' => $timeline->user_type,
                        ],
                    ];
                });
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
