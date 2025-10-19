<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class ListTransactionRequest extends FormRequest
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
            'type' => 'nullable|string|in:deposit,withdraw',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'per_page' => 'nullable|integer|min:1',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'type.in' => 'Transaction type must be either deposit or withdraw',
            'from_date.date' => 'From date must be a valid date',
            'to_date.date' => 'To date must be a valid date',
            'to_date.after_or_equal' => 'To date must be after or equal to from date',
            'per_page.integer' => 'Per page value must be a number',
            'per_page.min' => 'Per page value must be at least 1',
        ];
    }
}
