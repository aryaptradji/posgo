<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Product::latest()->get()->map(function ($product) {
            $status = $product->stock === 0 ? 'Habis' : ($product->stock <= 5 ? 'Sedikit' : 'Banyak');

            return [
                'Nama' => $product->name,
                'Stok' => (string) $product->stock,
                'Pcs' => (string) $product->pcs,
                'Status' => $status,
                'Harga' => (string) $product->price
            ];
        });
    }

    public function headings(): array {
        return ['Nama', 'Stok', 'Pcs', 'Status', 'Harga'];
    }

    public function styles(Worksheet $sheet) {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
