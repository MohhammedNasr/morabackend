<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class ListSuppliersByCategoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categories_ids' => 'required|array',
            'categories_ids.*' => 'required|exists:categories,id',
            'search' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'categories_ids.required' => 'Categories IDs are required',
            'categories_ids.array' => 'Categories IDs must be an array',
            'categories_ids.*.required' => 'Each category ID is required',
            'categories_ids.*.exists' => 'One or more selected categories do not exist',
            'search.string' => 'Search term must be a string',
            'is_active.boolean' => 'Active status must be true or false',
            'per_page.integer' => 'Per page must be a number',
            'per_page.min' => 'Per page must be at least 1',
        ];
    }
}
