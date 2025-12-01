<?php

declare(strict_types=1);

namespace App\Enums;

enum HeadBedElevated: string
{
    case SELFREGULATED = 'selfregulated';
    case FLAT = 'flat';
    case THIRTYDEG = '30deg';
    case SIXTYDEG = '60deg';
    case NINETY = '90deg';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::SELFREGULATED => 'Self Regulated',
            self::FLAT => 'Flat',
            self::THIRTYDEG => '30 Deg',
            self::SIXTYDEG => '60 Deg',
            self::NINETY => '90 Deg',
            self::OTHER => 'Other',
        };
    }
}
