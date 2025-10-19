<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class ChargeWalletRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount' => 'required|numeric|min:1',
            'customer.email' => 'required|email',
            'customer.givenName' => 'required|string',
            'customer.surname' => 'required|string',
            'billing.street1' => 'required|string',
            'billing.city' => 'required|string',
            'billing.state' => 'required|string',
            'billing.country' => 'required|string|size:2',
            'billing.postcode' => 'required|string'
        ];
    }

    public function prepareForValidation()
    {
        $user = $this->user();
        $this->mergeUserData($user);
    }

    protected function mergeUserData($user)
    {
        if (empty($user->email)) {
            throw new \RuntimeException('User email is required');
        }

        // Split full name into first and last names
        $nameParts = explode(' ', $user->name, 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        $customerData = [
            'email' => $user->email,
            'givenName' => $firstName,
            'surname' => $lastName
        ];

        // Use default values for billing since User model doesn't have these fields
        $billingData = [
            'street1' => $this->input('billing.street1', 'ffff'),
            'city' => $this->input('billing.city', 'ffff'),
            'state' => $this->input('billing.state', 'fffff'),
            'country' => $this->input('billing.country', 'SA'),
            'postcode' => $this->input('billing.postcode', '5555555')
        ];

        $this->merge([
            'customer' => array_merge($this->customer ?? [], $customerData),
            'billing' => array_merge($this->billing ?? [], $billingData)
        ]);
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        $this->mergeUserData($this->user());

        // Ensure merged data is included
        $mergedData = [
            'customer' => $this->input('customer', []),
            'billing' => $this->input('billing', [])
        ];


        return array_merge($data, $mergedData);
    }
}
