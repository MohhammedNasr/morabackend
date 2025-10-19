<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\OrderItem;

class ModifiedSupplierItemResource extends JsonResource
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
            'product' => [
                'id' => $this->product->id,
                'name' => $this->product->getName(),
                'category_name' => $this->product->category->{'name_' . app()->getLocale()},
                'image' => asset($this->product->image),
                'sku' => $this->product->sku,
            ],
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => $this->total_price,

            'previous_quantity' => $this->previous_quantity,
            'previous_unit_price' => $this->previous_unit_price,
            'previous_total_price' => $this->previous_total_price,
            'modification_notes' => $this->modification_notes,
        ];
    }
}
