<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashierExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::where('role', 'cashier')
            ->orderBy('created', 'desc')
            ->get()
            ->map(function ($cashier) {
                return [
                    'Nama' => $cashier->name,
                    'Email' => $cashier->email,
                    'Password' => $cashier->visible_password,
                    'No Handphone' => $cashier->phone_number,
                    'Waktu Dibuat' => $cashier->created->translatedFormat('d M Y H:i:s')
                ];
            });
    }

    public function headings(): array {
        return ['Nama', 'Email', 'Password', 'No Handphone', 'Waktu Dibuat'];
    }

    public function styles(Worksheet $sheet) {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }
}
