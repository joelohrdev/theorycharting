<?php

declare(strict_types=1);

namespace App\Enums;

enum BpMethod: string
{
    case NIBPCUFF = 'nibpcuff';
    case MANUAL = 'manual';
    case ARTERIALLINE = 'arterialline';

    public function label(): string
    {
        return match ($this) {
            self::NIBPCUFF => 'NIBP Cuff',
            self::MANUAL => 'Manual',
            self::ARTERIALLINE => 'Arterial Line',
        };
    }
}
