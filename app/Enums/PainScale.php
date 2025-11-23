<?php

declare(strict_types=1);

namespace App\Enums;

enum PainScale: string
{
    case NUMERIC = 'numeric';
    case FACE = 'face';

    public function label(): string
    {
        return match ($this) {
            self::NUMERIC => 'Numeric',
            self::FACE => 'Face',
        };
    }
}
