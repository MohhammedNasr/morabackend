<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRepresentativeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'email' => 'nullable|email|unique:representatives,email,'.$this->representative->id,
            'phone' => 'sometimes|string|unique:representatives,phone,'.$this->representative->id,
            'password' => 'sometimes|string|min:8',
            'is_active' => 'sometimes|boolean'
        ];
    }
}
