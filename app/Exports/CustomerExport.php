<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerExport implements FromCollection, WithHeadings, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::with(['address.neighborhood.subDistrict.district.city'])
            ->where('role', 'customer')
            ->get()
            ->map(function ($c) {
                return [
                    'Nama' => $c->name,
                    'Email' => $c->email,
                    'No HP' => $c->phone_number,
                    'Alamat' => $c->address->street ?? '-',
                    'RT' => $c->address->neighborhood->rt ?? '-',
                    'RW' => $c->address->neighborhood->rw ?? '-',
                    'Kelurahan' => $c->address->neighborhood->subDistrict->name ?? '-',
                    'Kecamatan' => $c->address->neighborhood->subDistrict->district->name ?? '-',
                    'Kota' => $c->address->neighborhood->subDistrict->district->city->name ?? '-',
                    'Kode Pos' => $c->address->neighborhood->postal_code ?? '-',
                    'Waktu Dibuat' => $c->created->translatedFormat('d M Y H:i'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'No HP',
            'Alamat',
            'RT',
            'RW',
            'Kelurahan',
            'Kecamatan',
            'Kota',
            'Kode Pos',
            'Waktu Dibuat',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
