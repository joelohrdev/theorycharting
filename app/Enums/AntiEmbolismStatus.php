<?php

declare(strict_types=1);

namespace App\Enums;

enum AntiEmbolismStatus: string
{
    case NA = 'na';
    case REFUSED = 'refused';
    case APPLIED = 'applied';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::NA => 'N/A',
            self::REFUSED => 'Refused',
            self::APPLIED => 'Applied',
            self::OTHER => 'Other',
        };
    }
}
