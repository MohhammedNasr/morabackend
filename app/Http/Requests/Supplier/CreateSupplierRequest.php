<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class CreateSupplierRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers',
            'phone' => 'required|string|unique:suppliers',
            'password' => 'required|string|min:8',
            'commercial_record' => 'required|string|unique:suppliers',
            'tax_id' => 'required|nullable|string',
            'bank_id' => 'required|exists:banks,id',
            'account_owner_name' => 'required|nullable|string',
            'bank_account' => 'required|string|digits:18',
            'iban_number' => 'required|string|regex:/^SA\d{22}$/|unique:suppliers',
            'id_number' => 'required|string|unique:suppliers',
            'address' => 'required|string',
            'contact_name' => 'nullable|string',
            'payment_term_days' => 'nullable|integer|min:1|max:365',
            'website' => 'nullable|url|max:255',
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
            'email.required' => 'Email is required',
            'email.email' => 'Must be a valid email address',
            'email.unique' => 'Email already registered',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'Phone number already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'commercial_record.required' => 'Commercial record number is required',
            'commercial_record.unique' => 'This commercial record is already registered',
            'address.required' => 'Address is required',
            'payment_term_days.integer' => 'Payment term days must be a number',
            'payment_term_days.min' => 'Payment term days must be at least 1',
            'payment_term_days.max' => 'Payment term days cannot exceed 365',
            'bank_account.required' => 'Bank account number is required',
            'bank_account.digits' => 'Bank account must be exactly 18 digits',
            'iban_number.required' => 'IBAN number is required',
            'iban_number.regex' => 'IBAN must be in SA format (SAxxxxxxxxxxxxxxxxxxxxxx)',
            'website.url' => 'Must be a valid URL',
        ];
    }
}
