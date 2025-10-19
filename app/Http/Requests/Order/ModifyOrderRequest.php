<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ModifyOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('modify', $this->route('order'));
    }

    public function rules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.action' => 'required|in:update,delete'
        ];
    }
}
