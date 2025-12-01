<?php

declare(strict_types=1);

namespace App\Enums;

enum StoolAmount: string
{
    case LARGE = 'large';
    case MEDIUM = 'medium';
    case SMALL = 'small';
    case SMEAR = 'smear';
    case UTA = 'uta';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::LARGE => 'Large',
            self::MEDIUM => 'Medium',
            self::SMALL => 'Small',
            self::SMEAR => 'Smear',
            self::UTA => 'UTA',
            self::OTHER => 'Other',
        };
    }
}
