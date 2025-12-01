<?php

declare(strict_types=1);

namespace App\Enums;

enum TransportMethod: string
{
    case WHEELCHAIR = 'wheelchair';
    case CANE = 'cane';
    case WALKER = 'walker';
    case TRANSFERBOARD = 'transferboard';
    case TRANSFERSHEET = 'transfersheet';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::WHEELCHAIR => 'Wheelchair',
            self::CANE => 'Cane',
            self::WALKER => 'Walker',
            self::TRANSFERBOARD => 'Transfer Board',
            self::TRANSFERSHEET => 'Transfer Sheet',
            self::OTHER => 'Other',
        };
    }
}
