<?php

namespace App\Imports;

use App\Models\Product;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductsSpreadsheetImport
{
    private $supplier_id;
    private $errors = [];
    private $success = false;
    private $processedRows = 0;

    public function __construct($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function import($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            array_shift($rows);

            foreach ($rows as $row) {
                $this->processedRows++;

                if (empty(array_filter($row))) {
                    continue;
                }

                $data = [
                    'category_id' => $row[0] ?? null,
                    'name_en' => $row[1] ?? null,
                    'name_ar' => $row[2] ?? null,
                    'description_en' => $row[3] ?? null,
                    'description_ar' => $row[4] ?? null,
                    'image' => $row[5] ?? null,
                    'sku' => $row[6] ?? null,
                    'price' => $row[7] ?? null,
                    'price_before' => $row[8] ?? null,
                    'available_quantity' => $row[9] ?? null,
                    'status' => $row[10] ?? null,
                    'has_discount' => $row[11] ?? false,
                ];

                Log::info('Processing import row:', $data);

                $validator = Validator::make($data, [
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
                ]);

                if ($validator->fails()) {
                    $this->errors[] = [
                        'row' => $this->processedRows,
                        'errors' => $validator->errors()->all(),
                        'values' => $data
                    ];
                    continue;
                }

                $product = Product::create(array_merge($data, [
                    'supplier_id' => $this->supplier_id
                ]));

                Log::info('Successfully created product:', $product->toArray());
                $this->success = true;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Import failed: ' . $e->getMessage());
            $this->success = false;
            return false;
        }
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
            return "Successfully imported products";
        }

        if (!empty($this->errors)) {
            return "Import completed with errors in {$this->processedRows} rows";
        }

        return "Import failed for all rows";
    }
}
