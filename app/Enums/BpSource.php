<?php

declare(strict_types=1);

namespace App\Enums;

enum BpSource: string
{
    case LEFTARM = 'leftarm';
    case RIGHTARM = 'rightarm';
    case LEFTLEG = 'leftleg';
    case RIGHTLEG = 'rightleg';

    public function label(): string
    {
        return match ($this) {
            self::LEFTARM => 'Left Arm',
            self::RIGHTARM => 'Right Arm',
            self::LEFTLEG => 'Left Leg',
            self::RIGHTLEG => 'Right Leg',
        };
    }
}
