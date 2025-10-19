<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;

class GenerateProductImportTemplate extends Command
{
    protected $signature = 'template:generate-products';
    protected $description = 'Generate products import template';

    public function handle()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'Store ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Quantity');
        $sheet->setCellValue('F1', 'Category');
        $sheet->setCellValue('G1', 'SKU');
        $sheet->setCellValue('H1', 'Barcode');

        // Auto size columns
        foreach (range('A','H') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create storage directory if not exists
        Storage::makeDirectory('public/templates');

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path('app/public/templates/products-import-template.xlsx'));

        $this->info('Product import template generated successfully!');
    }
}
