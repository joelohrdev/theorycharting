<?php

declare(strict_types=1);

namespace App\Enums;

enum NailCare: string
{
    case TRIMMED = 'trimmed';
    case CLEANED = 'cleaned';
    case LOTIONAPPLIED = 'lotionapplied';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::TRIMMED => 'Trimmed',
            self::CLEANED => 'Cleaned',
            self::LOTIONAPPLIED => 'Lotion Applied',
            self::OTHER => 'Other',
        };
    }
}
