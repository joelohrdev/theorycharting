<?php

declare(strict_types=1);

namespace App\Enums;

enum Hair: string
{
    case WASH = 'wash';
    case COMB = 'comb';
    case BRUSH = 'brush';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WASH => 'Wash',
            self::COMB => 'Comb',
            self::BRUSH => 'Brush',
            self::OTHER => 'Other',
        };
    }
}
