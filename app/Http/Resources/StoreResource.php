<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\StoreBranchResource;

class StoreResource extends JsonResource
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
            'name' => $this->name,
            'type' => $this->type,
            'credit_balance' => $this->credit_balance,
            'branches_count' => $this->branches_count,
            'commercial_record' => $this->commercial_record,
            'tax_number' => $this->tax_number,
            'id_number' => $this->id_number,
            'is_verified' => $this->is_verified,
            'branches' => StoreBranchResource::collection($this->whenLoaded('branches')),
        ];
    }
}
