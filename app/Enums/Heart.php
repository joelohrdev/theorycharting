<?php

declare(strict_types=1);

namespace App\Enums;

enum Heart: string
{
    case MONITORED = 'monitored';
    case APICAL = 'apical';
    case RADIAL = 'radial';

    public function label(): string
    {
        return match ($this) {
            self::MONITORED => 'Monitored',
            self::APICAL => 'Apical',
            self::RADIAL => 'Radial',
        };
    }
}
