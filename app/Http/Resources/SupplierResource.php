<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contact_name' => $this->contact_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'commercial_record' => $this->commercial_record,
            'tax_id' => $this->tax_id,
            'bank_account' => $this->bank_account,
            'iban_number' => $this->iban_number,
            'bank_name' => $this->bank?->{'name_' . app()->getLocale()},
            'account_owner_name' => $this->account_owner_name,
            'website' => $this->website,
            'payment_term_days' => $this->payment_term_days,
            'is_active' => $this->is_active,
            'categories' => $this->whenLoaded('categories', function () {
                return CategoryResource::collection($this->categories);
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    // public function user($request): array
    // {
    //     return [
    //         'id' => $this->id,
    //         'name' => $this->name,
    //         'role' => $this->role->slug,
    //         'role_name' => $this->role->name,
    //         'email' => $this->email,
    //         'phone' => $this->phone,
    //         'created_at' => $this->created_at,
    //         'updated_at' => $this->updated_at,
    //     ];
    // }
}
