<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SupplierExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Supplier::latest()
            ->get()
            ->map(function ($supplier) {
                return [
                    'Nama' => $supplier->name,
                    'Telepon' => $supplier->phone,
                    'Email' => $supplier->email,
                    'Fax' => $supplier->fax,
                    'Alamat' => $supplier->address,
                ];
            });
    }

    public function headings(): array
    {
        return ['Nama', 'Telepon', 'Email', 'Fax', 'Alamat'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
