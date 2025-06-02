<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements FromCollection, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::with(['user', 'items'])->orderBy('time', 'desc')->get()->map(function ($o) {
            return [
                'Kode' => $o->code,
                'Waktu' => $o->time,
                'Nama' => $o->user->name,
                'Kategori' => $o->category,
                'Status' => $o->status,
                'Item' => $o->item,
                'Total' => $o->total
            ];
        });
    }

    public function headings(): array {
        return [
            'Kode',
            'Waktu',
            'Nama',
            'Kategori',
            'Status',
            'Item',
            'Total'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
