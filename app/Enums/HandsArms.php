<?php

declare(strict_types=1);

namespace App\Enums;

enum HandsArms: string
{
    case PILLOWS = 'pillows';
    case BLANKETS = 'blankets';
    case TOWELS = 'towels';
    case ELEVATIONDEVICE = 'elevationdevice';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PILLOWS => 'Pillows',
            self::BLANKETS => 'Blankets',
            self::TOWELS => 'Towels',
            self::ELEVATIONDEVICE => 'Elevation Device',
            self::OTHER => 'Other',
        };
    }
}
