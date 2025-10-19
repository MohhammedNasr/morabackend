<?php

namespace App\Http\Requests\SubOrder;

use Illuminate\Foundation\Http\FormRequest;

class ModifySubOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
       // return $this->user()->can('modifySubOrder', $this->route('subOrder'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'items' => 'required|array',
            'items.*.id' => 'required|integer|exists:order_items,id',
            'items.*.action' => 'required|in:update,delete',
            'items.*.quantity' => 'required_if:action,update|integer|min:1',
            'items.*.notes' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.required' => 'At least one item modification is required',
            'items.*.id.exists' => 'One or more items do not exist',
            'items.*.action.in' => 'Action must be either update or delete',
            'items.*.quantity.required_if' => 'Quantity is required when updating an item',
            'items.*.quantity.min' => 'Quantity must be at least 1'
        ];
    }
}
