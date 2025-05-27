<?php

namespace App\Exports;

use App\Models\Courier;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CourierExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Courier::latest()->get()->map(function ($courier) {
            return [
                'Nama' => $courier->name,
                'No Handphone' => $courier->phone,
                'Email' => $courier->email,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama', 'No Handphone', 'Email'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
