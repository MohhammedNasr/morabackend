<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierProfileRequest extends FormRequest
{
    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:suppliers,email,'.$this->user()->id,
            'phone' => 'sometimes|required|string|max:20',
            'commercial_record' => 'sometimes|required|string|unique:suppliers,commercial_record,'.$this->user()->id,
            'address' => 'sometimes|required|string',
            'contact_name' => 'sometimes|nullable|string',
            'tax_id' => 'sometimes|nullable|string',
            'bank_account' => 'sometimes|nullable|string',
            'iban_number' => 'sometimes|nullable|string',
            'id_number' => 'sometimes|nullable|string',
            'bank_id' => 'sometimes|nullable|integer|exists:banks,id',
            'account_owner_name' => 'sometimes|nullable|string',
            'website' => 'sometimes|nullable|url',
        ];
    }
}
