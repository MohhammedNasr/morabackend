<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CreateOrderRequest extends FormRequest
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
     * @return array<string, \Illuitminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
       
        return [
            'store_branch_id' => [
                'required',
                $this->user()->role && $this->user()->role->slug === 'store-owner'
                    ? Rule::exists('store_branches', 'id')->where(function ($query) {
                        $query->whereExists(function ($subQuery) {
                            $subQuery->select(DB::raw(1))
                                ->from('store_users')
                                ->whereColumn('store_users.store_id', 'store_branches.store_id')
                                ->where('store_users.user_id', $this->user()->id);
                        });
                    })
                    : Rule::exists('store_branches', 'id')
            ],
          //  'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ];
    }
}
