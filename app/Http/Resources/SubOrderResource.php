<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'supplier' => [
                'id' => $this->supplier->id,
                'name' => $this->supplier->name,
            ],
            'representative' => $this->when($this->representative, function () {
                return [
                    'id' => $this->representative->id,
                    'name' => $this->representative->name
                ];
            }),
            'store' => new StoreResource($this->order->store),
            'products_count' => $this->products_count,
            'total_amount' => $this->total_amount,
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'promotion_id' => $this->promotion_id,
        ];
    }
}
