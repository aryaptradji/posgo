<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case BelumDibayar = 'Belum Dibayar';
    case Dibayar = 'Dibayar';
    case Batal = 'Batal';

    public function label(): string
    {
        return match ($this) {
            self::BelumDibayar => 'Belum Dibayar',
            self::Dibayar      => 'Dibayar',
            self::Batal        => 'Batal',
        };
    }
}
