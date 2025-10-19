<?php

namespace App\Http\Requests\Store;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'street_name' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'city_id' => ['required', 'exists:cities,id'],
            'area_id' => ['exists:areas,id'],
            'notes' => ['nullable', 'string'],
            'main_name' => ['nullable', 'string', 'max:255'],
            'building_number' => ['nullable', 'string'],
            'floor_number' => ['nullable', 'string'],
        ];
    }
}
