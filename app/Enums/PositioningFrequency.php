<?php

declare(strict_types=1);

namespace App\Enums;

enum PositioningFrequency: string
{
    case ABLETOTURNSELF = 'abletoturnself';
    case Q1HR = 'q1hr';
    case Q2HR = 'q2hr';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ABLETOTURNSELF => 'Able to Turn Self',
            self::Q1HR => 'Q1 Hr',
            self::Q2HR => 'Q2 Hr',
            self::OTHER => 'Other',
        };
    }
}
