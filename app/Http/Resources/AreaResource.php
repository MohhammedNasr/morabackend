<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->{'name_' . app()->getLocale()},
            // 'name_en' => $this->name_en,
            'code' => $this->code,
            'city_id' => $this->city_id,
            'city' => new CityResource($this->whenLoaded('city')),
        ];
    }
}
