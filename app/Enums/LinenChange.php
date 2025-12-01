<?php

declare(strict_types=1);

namespace App\Enums;

enum LinenChange: string
{
    case FULLBED = 'fullbed';
    case PARTIALBED = 'partialbed';
    case DRAWSHEET = 'drawsheet';
    case PILLOWCASE = 'pillowcase';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::FULLBED => 'Full Bed',
            self::PARTIALBED => 'Partial Bed',
            self::DRAWSHEET => 'Draw Sheet',
            self::PILLOWCASE => 'Pillow Case',
            self::OTHER => 'Other',
        };
    }
}
