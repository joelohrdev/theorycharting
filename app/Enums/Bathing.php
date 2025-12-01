<?php

declare(strict_types=1);

namespace App\Enums;

enum Bathing: string
{
    case COMPLETEDBEDBATH = 'completedbedbath';
    case PARTIALBEDBATH = 'partialbedbath';
    case SHOWER = 'shower';
    case SINKBATH = 'sinkbath';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::COMPLETEDBEDBATH => 'Completed Bed Bath',
            self::PARTIALBEDBATH => 'Partial Bed Bath',
            self::SHOWER => 'Shower',
            self::SINKBATH => 'Sink Bath',
            self::OTHER => 'Other',
        };
    }
}
