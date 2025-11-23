<?php

declare(strict_types=1);

namespace App\Enums;

enum PainDescriptors: string
{
    case SHARP = 'sharp';
    case DULL = 'dull';
    case ACHING = 'aching';
    case BURNING = 'burning';
    case THROBBING = 'throbbing';
    case STABBING = 'stabbing';
    case SHOOTING = 'shooting';
    case TINGLING = 'tingling';
    case CRAMPING = 'cramping';
    case HEAVY = 'heavy';
    case RADIATING = 'radiating';
    case CRYING = 'crying';
    case GRIMACING = 'grimacing';
    case FROWNING = 'frowning';
    case GUARDING = 'guarding';
    case RETRACTING = 'retracting';

    public function label(): string
    {
        return match ($this) {
            self::SHARP => 'Sharp',
            self::DULL => 'Dull',
            self::ACHING => 'Aching',
            self::BURNING => 'Burning',
            self::THROBBING => 'Throbbing',
            self::STABBING => 'Stabbing',
            self::SHOOTING => 'Shooting',
            self::TINGLING => 'Tingling',
            self::CRAMPING => 'Cramping',
            self::HEAVY => 'Heavy',
            self::RADIATING => 'Radiating',
            self::CRYING => 'Crying',
            self::GRIMACING => 'Grimacing',
            self::FROWNING => 'Frowning',
            self::GUARDING => 'Guarding',
            self::RETRACTING => 'Retracting',
        };
    }
}
