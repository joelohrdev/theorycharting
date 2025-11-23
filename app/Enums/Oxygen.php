<?php

declare(strict_types=1);

namespace App\Enums;

enum Oxygen: string
{
    case RA = 'ra';
    case NASALCANUAL = 'nasalcanual';
    case MASK = 'mask';

    public function label(): string
    {
        return match ($this) {
            self::RA => 'RA',
            self::NASALCANUAL => 'Nasal Canual',
            self::MASK => 'Mask',
        };
    }
}
