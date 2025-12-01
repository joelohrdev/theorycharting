<?php

declare(strict_types=1);

namespace App\Enums;

enum HandsFeet: string
{
    case FOOTOFBEDELEVATED = 'footofbedelevated';
    case PILLOWS = 'pillows';
    case BLANKET = 'blanket';
    case TOWEL = 'towel';
    case ELEVATIONDEVICE = 'elevationdevice';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::FOOTOFBEDELEVATED => 'Foot of Bed Elevated',
            self::PILLOWS => 'Pillows',
            self::BLANKET => 'Blanket',
            self::TOWEL => 'Towel',
            self::ELEVATIONDEVICE => 'Elevation Device',
            self::OTHER => 'Other',
        };
    }
}
