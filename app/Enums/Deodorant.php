<?php

declare(strict_types=1);

namespace App\Enums;

enum Deodorant: string
{
    case APPLIED = 'applied';
    case NOTAPPLIED = 'notapplied';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::APPLIED => 'Applied',
            self::NOTAPPLIED => 'Not Applied',
            self::OTHER => 'Other',
        };
    }
}
