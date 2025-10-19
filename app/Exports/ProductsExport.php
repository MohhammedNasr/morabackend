<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::with(['category', 'supplier'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Category',
            'Supplier',
            'Name (EN)',
            'Name (AR)',
            'Description (EN)',
            'Description (AR)',
            'SKU',
            'Price',
            'Quantity',
            'Status'
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->category->name_en,
            $product->supplier->name,
            $product->name_en,
            $product->name_ar,
            $product->description_en,
            $product->description_ar,
            $product->sku,
            $product->price,
            $product->available_quantity,
            $product->status
        ];
    }
}
