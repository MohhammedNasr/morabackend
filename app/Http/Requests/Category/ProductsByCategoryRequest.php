<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class ProductsByCategoryRequest extends FormRequest
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
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'exists:categories,id'],
            'suppliers' => ['nullable', 'exists:suppliers,id'],
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'categories.required' => 'At least one category must be selected',
            'categories.array' => 'Categories must be provided as an array',
            'categories.*.exists' => 'Selected category does not exist',
            'supplier_id.exists' => 'Selected supplier does not exist',
            'search.max' => 'Search term cannot exceed 255 characters',
            'page.integer' => 'Page value must be a number',
            'page.min' => 'Page value must be at least 1',
            'per_page.integer' => 'Per page value must be a number',
            'per_page.min' => 'Per page value must be at least 1',
            'per_page.max' => 'Per page value cannot exceed 100',
        ];
    }
}
