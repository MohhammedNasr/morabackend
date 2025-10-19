<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
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
            'type' => 'required|string|in:user,representative,supplier,store-owner',
            'phone' => 'required|string',
            'password' => 'required|string',
            'uuid' => 'nullable|string',
            'token' => 'nullable|string',
            'platform' => 'nullable|string',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $data['token'] = $this->header('X-Device-Token');
        $data['platform'] = $this->header('X-Device-Type');
        return $data;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required',
            'password.required' => 'Password is required',
        ];
    }
}
