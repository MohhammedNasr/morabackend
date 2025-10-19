<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'email' => $this->email,
            'phone' => $this->phone,
            'phone2' => $this->phone2,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'snapchat' => $this->snapchat,
            'whatsapp' => $this->whatsapp,
        ];
    }
}
