<?php

namespace App\Http\Requests\Store;

use App\Models\Store;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'unique:users', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'device_token' => ['nullable', 'string'],
            'uuid' => ['nullable', 'string'],
            'stores' => ['required', 'array'],
            'stores.name' => ['required', 'string', 'max:255'],
            'stores.type' => ['required', 'string', Rule::in(Store::TYPES)],
            'stores.commercial_record' => ['required', 'string', 'max:255', 'unique:stores'],
            'stores.tax_record' => ['required', 'string', 'max:255', 'unique:stores'],
            'stores.id_number' => ['required', 'string', 'max:255', 'unique:stores'],
            'branch' => ['required', 'array'],
            'branch.*.name' => ['required', 'string', 'max:255'],
            'branch.*.phone' => ['required', 'string', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'branch.*.address' => ['required', 'string'],
            'branch.*.latitude' => ['required', 'numeric'],
            'branch.*.longitude' => ['required', 'numeric'],
            'branch.*.city_id' => ['required', 'exists:cities,id'],
            'branch.*.area_id' => ['exists:areas,id'],
            'branch.*.notes' => ['nullable', 'string'],
            'branch.*.main_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Name cannot exceed 255 characters',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'phone.required' => 'Phone number is required',
            'phone.unique' => 'This phone number is already registered',
            'stores.required' => 'Store information is required',
            'stores.name.required' => 'Store name is required',
            'stores.type.required' => 'Store type is required',
            'stores.type.in' => 'Invalid store type',
            'stores.commercial_record.required' => 'Commercial record is required',
            'stores.commercial_record.unique' => 'This commercial record is already registered',
            'stores.tax_record.required' => 'Tax record is required',
            'stores.tax_record.unique' => 'This tax record is already registered',
            'stores.id_number.required' => 'ID number is required',
            'stores.id_number.unique' => 'This ID number is already registered',
            'branch.required' => 'Branch information is required',
            'branch.*.name.required' => 'Branch name is required',
            'branch.*.phone.required' => 'Branch phone is required',
          //  'branch.*.address.required' => 'Branch address is required',
            'branch.*.latitude.required' => 'Branch latitude is required',
            'branch.*.latitude.numeric' => 'Branch latitude must be a number',
            'branch.*.longitude.required' => 'Branch longitude is required',
            'branch.*.longitude.numeric' => 'Branch longitude must be a number',
        ];
    }
}
