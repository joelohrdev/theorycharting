<?php

declare(strict_types=1);

namespace App\Enums;

enum Temp: string
{
    case ORAL = 'oral';
    case AXILLARY = 'axillary';
    case RECTAL = 'rectal';
    case TYMPANIC = 'tympanic';
    case TEMPORAL = 'temporal';

    public function label(): string
    {
        return match ($this) {
            self::ORAL => 'Oral',
            self::AXILLARY => 'Axillary',
            self::RECTAL => 'Rectal',
            self::TYMPANIC => 'Tympanic',
            self::TEMPORAL => 'Temporal',
        };
    }
}
