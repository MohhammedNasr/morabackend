<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ListProductRequest extends FormRequest
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
            'category_id' => 'nullable|exists:categories,id',
            'search' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'per_page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.exists' => 'Selected category does not exist',
            'supplier_id.exists' => 'Selected supplier does not exist',
            'per_page.integer' => 'Per page value must be a number',
            'per_page.min' => 'Per page value must be at least 1',
        ];
    }
}
