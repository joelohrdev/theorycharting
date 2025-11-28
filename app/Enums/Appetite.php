<?php

declare(strict_types=1);

namespace App\Enums;

enum Appetite: string
{
    case GOOD = 'good';
    case FAIR = 'fair';
    case POOR = 'poor';
    case NOAPPETITE = 'noappetite';
    case REFUSETOEAT = 'refusetoeat';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::GOOD => 'Good',
            self::FAIR => 'Fair',
            self::POOR => 'Poor',
            self::NOAPPETITE => 'No Appetite',
            self::REFUSETOEAT => 'Refuse to Eat',
            self::OTHER => 'Other',
        };
    }
}
