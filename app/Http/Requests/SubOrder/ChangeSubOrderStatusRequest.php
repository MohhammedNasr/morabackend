<?php

namespace App\Http\Requests\SubOrder;

use Illuminate\Foundation\Http\FormRequest;

class ChangeSubOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,acceptedBySupplier,rejectedBySupplier,assignToRep,rejectedByRep,acceptedByRep,modifiedBySupplier,modifiedByRep,outForDelivery,delivered',
            'rejection_id' => 'required_if:status,rejectedBySupplier,rejectedByRep|nullable|numeric|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status is required',
            'status.in' => 'Invalid status value',
            'reason.required_if' => 'Rejection Id is required when rejecting or canceling',
            'reason.numeric' => 'Rejection must be a number or an integer',
            'reason.max' => 'Rejection cannot exceed 255 characters'
        ];
    }
}
