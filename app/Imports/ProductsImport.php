<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    private $supplier_id;
    private $errors = [];
    private $success = false;

    public function __construct($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function success()
    {
        return $this->success;
    }

    public function getMessage()
    {
        if ($this->success) {
            return 'Products imported successfully!';
        }

        if (!empty($this->errors)) {
            return 'Import completed with errors. Please check the error messages below.';
        }

        return 'Import failed. Please check your file and try again.';
    }

    public function model(array $row)
    {
        try {
            Log::info('Processing import row:', $row);


            $this->success = true;
            $product = new Product([
                'supplier_id' => $this->supplier_id ?? Auth::guard('supplier-web')->user()->id,
                'category_id' => $row['category_id'],
                'name_en' => $row['name_en'],
                'name_ar' => $row['name_ar'],
                'description_en' => $row['description_en'] ?? null,
                'description_ar' => $row['description_ar'] ?? null,
                'image' => $row['image'] ?? null,
                'sku' => $row['sku'] ?? null,
                'price' => $row['price'],
                'price_before' => $row['price_before'] ?? null,
                'available_quantity' => $row['available_quantity'],
                'status' => $row['status'] ?? 'active',
                'has_discount' => $row['has_discount'] ?? false,
            ]);

            Log::info('Successfully created product:', $product->toArray());
            return $product;
        } catch (\Exception $e) {

            Log::error('Product import row error: ' . $e->getMessage(), [
                'row' => $row,
                'exception' => $e
            ]);
            $this->success = false;
            return null;
        }
    }


    public function rules(): array
    {
        return [
            //  'supplier_id' => 'required|exists:suppliers,id',
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'price_before' => 'nullable|numeric|min:0',
            'available_quantity' => 'required|integer|min:0',
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'has_discount' => 'nullable|boolean',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'supplier_id.required' => __('messages.products.validation.supplier_id.required'),
            'supplier_id.exists' => __('messages.products.validation.supplier_id.exists'),
            'category_id.required' => __('messages.products.validation.category_id.required'),
            'category_id.exists' => __('messages.products.validation.category_id.exists'),
            'name_en.required' => __('messages.products.validation.name_en.required'),
            'name_ar.required' => __('messages.products.validation.name_ar.required'),
            'sku.required' => __('messages.products.validation.sku.required'),
            'sku.unique' => __('messages.products.validation.sku.unique'),
            'price.required' => __('messages.products.validation.price.required'),
            'price.numeric' => __('messages.products.validation.price.numeric'),
            'available_quantity.required' => __('messages.products.validation.available_quantity.required'),
            'available_quantity.integer' => __('messages.products.validation.available_quantity.integer'),
            'status.required' => __('messages.products.validation.status.required'),
            'status.in' => __('messages.products.validation.status.in'),
        ];
    }
}
