<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'total_amount' => $this->total_amount,
            'sub_total' => $this->sub_total ?? 0,
            'discount' => $this->discount ?? 0,
            'products_count' => $this->products_count,
            'categories_count' => $this->categories_count,
            'suppliers_count' => $this->suppliers_count,
            'suppliers' => $this->subOrders->map(function ($subOrder) {
                return [
                    'id' => $subOrder->supplier->id,
                    'name' => $subOrder->supplier->name,
                    'items_count' => $subOrder->items->count(),
                    'total_amount' => $subOrder->total_amount,
                ];
            }),
            'created_at' => $this->created_at,
            'payment_due_date' => $this->payment_due_date
        ];
    }
}
