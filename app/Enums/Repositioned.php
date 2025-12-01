<?php

declare(strict_types=1);

namespace App\Enums;

enum Repositioned: string
{
    case ABLETOTURNSELF = 'abletoturnself';
    case SUPINE = 'supine';
    case PRONE = 'prone';
    case LEFTLATERAL = 'leftrlateral';
    case RIGHTLATERAL = 'rightlateral';
    case SEMIFOWLERS = 'semifowlers';
    case FOWLERS = 'fowlers';
    case HIGHFOWLERS = 'highfowlers';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ABLETOTURNSELF => 'Able to Turn Self',
            self::SUPINE => 'Supine',
            self::PRONE => 'Prone',
            self::LEFTLATERAL => 'Left Lateral',
            self::RIGHTLATERAL => 'Right Lateral',
            self::SEMIFOWLERS => 'Semi-Fowlers',
            self::FOWLERS => 'Fowlers',
            self::HIGHFOWLERS => 'High Fowlers',
            self::OTHER => 'Other',
        };
    }
}
