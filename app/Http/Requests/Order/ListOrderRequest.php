<?php

namespace App\Http\Requests\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;

class ListOrderRequest extends FormRequest
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
            'type' => ['nullable', 'in:upcoming,historical'],
            'status' => ['nullable', 'in:' . implode(',', [
                Order::STATUS_PENDING,
                Order::STATUS_VERIFIED,
                Order::STATUS_UNDER_PROCESSING,
                Order::STATUS_CANCELED,
                Order::STATUS_COMPLETED,
            ])],
            'from_date' => ['nullable', 'date', 'required_with:to_date', 'before_or_equal:to_date'],
            'to_date' => ['nullable', 'date', 'required_with:from_date', 'after_or_equal:from_date'],
            'order_number' => ['nullable', 'string', 'max:50'],
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
            'type.in' => 'Order type must be either upcoming or historical',
            'status.in' => 'Invalid order status',
            'from_date.date' => 'From date must be a valid date',
            'to_date.date' => 'To date must be a valid date',
            'from_date.before_or_equal' => 'From date must be before or equal to to date',
            'to_date.after_or_equal' => 'To date must be after or equal to from date',
            'order_number.string' => 'Order number must be a string',
            'order_number.max' => 'Order number cannot exceed 50 characters',
            'per_page.integer' => 'Per page value must be a number',
            'page.integer' => 'Page value must be a number',
            'page.min' => 'Page value must be at least 1',
            'per_page.min' => 'Per page value must be at least 1',
            'per_page.max' => 'Per page value cannot exceed 100',
        ];
    }
}
