<?php

declare(strict_types=1);

namespace App\Enums;

enum LevelOfRepositioning: string
{
    case INDEPENDENT = 'independent';
    case ASSISTEDX1 = 'assistedx1';
    case ASSISTEDX2 = 'assistedx2';
    case TOTAL = 'total';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::INDEPENDENT => 'Independent',
            self::ASSISTEDX1 => 'Assisted X1',
            self::ASSISTEDX2 => 'Assisted X2',
            self::TOTAL => 'Total',
            self::OTHER => 'Other',
        };
    }
}
