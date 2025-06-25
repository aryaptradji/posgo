<?php

namespace App\Exports;

use App\Models\PurchaseOrder;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseOrderExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return PurchaseOrder::with(['items.product', 'supplier'])
            ->orderBy('created', 'desc')
            ->get()
            ->map(function ($po) {
                return [
                    'Waktu Dibuat' => $po->created,
                    'Nomor PO' => $po->code,
                    'Supplier' => $po->supplier->name,
                    'Status' => $po->status,
                    'Item' => $po->item,
                    'Total' => $po->total,
                ];
            });
    }

    public function headings(): array
    {
        return ['Waktu Dibuat', 'Nomor PO', 'Supplier', 'Status', 'Item', 'Total'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
