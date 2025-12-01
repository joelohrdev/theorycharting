<?php

declare(strict_types=1);

namespace App\Enums;

enum Shave: string
{
    case ELECTRIC = 'electric';
    case SHAVINGCREAM = 'shavingcream';
    case AFTERSHAVE = 'aftershave';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ELECTRIC => 'Electric',
            self::SHAVINGCREAM => 'Shaving Cream',
            self::AFTERSHAVE => 'Aftershave',
            self::OTHER => 'Other',
        };
    }
}
