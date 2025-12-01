<?php

declare(strict_types=1);

namespace App\Enums;

enum EmesisAmount: string
{
    case LARGE = 'large';
    case MEDIUM = 'medium';
    case SMALL = 'small';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::LARGE => 'Large',
            self::MEDIUM => 'Medium',
            self::SMALL => 'Small',
            self::OTHER => 'Other',
        };
    }
}
