<?php

declare(strict_types=1);

namespace App\Enums;

enum AmbulationResponse: string
{
    case TOLERATED = 'tolerated';
    case NOTTOLERATED = 'nottolerated';
    case STEADY = 'steady';
    case UNSTEADY = 'unsteady';
    case DIZZY = 'dizzy';
    case PAIN = 'pain';
    case NAUSEAU = 'nausea';
    case WEAKNESS = 'weakness';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::TOLERATED => 'Tolerated',
            self::NOTTOLERATED => 'Not Tolerated',
            self::STEADY => 'Steady',
            self::UNSTEADY => 'Unsteady',
            self::DIZZY => 'Dizzy',
            self::PAIN => 'Pain',
            self::NAUSEAU => 'Nausea',
            self::WEAKNESS => 'Weakness',
            self::OTHER => 'Other',
        };
    }
}
