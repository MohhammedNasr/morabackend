<?php

namespace App\Http\Resources;

use App\Models\OrderPayment;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class OrderPaymentResource extends JsonResource
{
    public function toArray($request)
    {
        $status = $this->status;

        // Check if payment is pending and is the first upcoming payment
        if ($status === OrderPayment::STATUS_PENDING) {
            $now = now()->startOfDay();

            $firstUpcoming = OrderPayment::where('order_id', $this->order_id)
                ->where('status', OrderPayment::STATUS_PENDING)
                ->whereDate('due_date', '>=', $now)
                ->orderBy('id', 'ASC')
                ->first();


            if ($firstUpcoming->id && $firstUpcoming->id === $this->id) {
                $status = OrderPayment::STATUS_DUE_TO_PAY;
            }
        }

        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'due_date' => $this->due_date,
            'status' => $status,
            'payment_method' => $this->payment_method,
            'transaction_number' => $this->transaction_number,
            'notes' => __("api.{$this->notes}"),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
