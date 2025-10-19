<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'commercial_record' => 'sometimes|required|string|unique:suppliers,commercial_record,' . $this->supplier->id,
            'payment_term_days' => 'sometimes|required|integer|min:1|max:365',
            'is_active' => 'sometimes|required|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Supplier name is required',
            'name.max' => 'Supplier name cannot exceed 255 characters',
            'commercial_record.required' => 'Commercial record number is required',
            'commercial_record.unique' => 'This commercial record is already registered',
            'payment_term_days.required' => 'Payment term days is required',
            'payment_term_days.integer' => 'Payment term days must be a number',
            'payment_term_days.min' => 'Payment term days must be at least 1',
            'payment_term_days.max' => 'Payment term days cannot exceed 365',
            'is_active.boolean' => 'Active status must be true or false',
        ];
    }
}
