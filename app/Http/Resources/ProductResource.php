<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SupplierResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            // 'name_en' => $this->name_en,
            'name' => $this->{'name_' . app()->getLocale()},
            'description' => $this->{'description_' . app()->getLocale()},
            // 'description' => $this->description_ar,
            'image' => asset($this->image),
            'sku' => $this->sku,
            'price' => $this->price,
            'has_discount' => $this->has_discount,
            'price_before' => $this->price_before,
            'available_quantity' => $this->available_quantity,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'supplier' => new SupplierResource($this->whenLoaded('supplier')),
            'pivot' => $this->whenPivotLoaded('supplier_products', function () {
                return [
                    'price' => $this->pivot->price,
                    'is_active' => $this->pivot->is_active,
                ];
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
