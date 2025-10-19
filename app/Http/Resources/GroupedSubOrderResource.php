<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\SubOrder;

class GroupedSubOrderResource extends JsonResource
{
    public function toArray($request)
    {
        // Get the date from the resource
        $date = $this->order_date;
        $orders = $this->orders; // Use the orders passed from the service

        return [
            'order_date' => $date,
            'total_orders' => $this->total_orders,
            'representative_id' => $this->representative_id ?? null,
            'supplier_id' => $this->supplier_id ?? null,
            'orders' => SubOrderResource::collection($orders),
        ];
    }
}
