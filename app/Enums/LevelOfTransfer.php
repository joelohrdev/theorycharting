<?php

declare(strict_types=1);

namespace App\Enums;

enum LevelOfTransfer: string
{
    case INDEPENDENT = 'independent';
    case ASSISTX1 = 'assistx1';
    case ASSISTX2 = 'assistx2';
    case TOTAL = 'total';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::INDEPENDENT => 'Independent',
            self::ASSISTX1 => 'Assisted X1',
            self::ASSISTX2 => 'Assisted X2',
            self::TOTAL => 'Total',
            self::OTHER => 'Other',
        };
    }
}
