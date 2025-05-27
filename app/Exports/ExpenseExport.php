<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpenseExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Expense::latest('date')->get()->map(function ($expense) {
            return [
                'Waktu' => $expense->date->translatedFormat('d M Y H:i:s'),
                'Sumber' => $expense->source,
                'Kategori' => $expense->category,
                'Total' => $expense->total
            ];
        });
    }

    public function headings(): array {
        return ['Waktu', 'Sumber', 'Kategori', 'Total'];
    }

    public function styles(Worksheet $sheet) {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
