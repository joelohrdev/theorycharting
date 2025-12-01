<?php

declare(strict_types=1);

namespace App\Enums;

enum AntiEmbolismDevice: string
{
    case NA = 'na';
    case SCD = 'scd';
    case RLE = 'rle';
    case LLE = 'lle';
    case BILEXTREMITIES = 'bilextremities';
    case TEDHOSE = 'tedhose';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::NA => 'N/A',
            self::SCD => 'SCD',
            self::RLE => 'RLE',
            self::LLE => 'LLE',
            self::BILEXTREMITIES => 'BIL Extremities',
            self::TEDHOSE => 'TED HOSE',
            self::OTHER => 'Other',
        };
    }
}
