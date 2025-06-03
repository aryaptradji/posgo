<?php

namespace App\Enums;

enum ShippingStatus: string
{
    case BelumDikirim = 'Belum Dikirim';
    case DalamPerjalanan = 'Dalam Perjalanan';
    case Selesai = 'Selesai';

    public function label(): string
    {
        return match ($this) {
            self::BelumDikirim => 'Belum Dibayar',
            self::DalamPerjalanan => 'Dalam Perjalanan',
            self::Selesai => 'Selesai',
        };
    }
}
