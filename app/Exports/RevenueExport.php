<?php

namespace App\Exports;

use App\Models\Revenue;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Revenue::with('product')->latest('date')->get()->map(function ($revenue) {
            return [
                'Waktu' => $revenue->date,
                'Sumber' => $revenue->source,
                'Kategori' => $revenue->category,
                'Total' => (string) $revenue->total,
            ];
        });
    }

    public function headings(): array
    {
        return ['Waktu', 'Sumber', 'Kategori', 'Total'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
