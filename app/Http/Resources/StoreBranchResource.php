<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\AreaResource;

class StoreBranchResource extends JsonResource
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
            'phone' => $this->phone,
            'store_id' => $this->store->id,
            'street_name' => $this->street_name,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_main' => $this->is_main,
            'is_active' => $this->is_active,
            'balance_limit' => (float) ($this->balance_limit ?? 0),
            'building_number' => $this->building_number,
            'floor_number' => $this->floor_number,
            'notes' => $this->notes,
            'main_name' => $this->main_name,
            'city' => CityResource::make($this->city) ?? null,
            'area' => AreaResource::make($this->area) ?? null,
            'address' => implode(', ', array_filter([
                $this->city?->name,
                $this->area?->name,
                $this->street_name,
                $this->building_number,
                $this->floor_number
            ])),
            // 'city' => new CityResource($this->whenLoaded('city')),
            // 'area' => new AreaResource($this->whenLoaded('area')),
        ];
    }
}
