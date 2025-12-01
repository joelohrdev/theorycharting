<?php

declare(strict_types=1);

namespace App\Enums;

enum OralCare: string
{
    case DENTURECARE = 'denturecare';
    case TOOTHBRUSH = 'toothbrush';
    case MOUTHSPONGE = 'mouthsponge';
    case MOUTHWASH = 'mouthwash';
    case SUCTIONTOOTHBRUSH = 'suctiontoothbrush';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::DENTURECARE => 'Denture Care',
            self::TOOTHBRUSH => 'Toothbrush',
            self::MOUTHSPONGE => 'Mouth Sponge',
            self::MOUTHWASH => 'Mouth Wash',
            self::SUCTIONTOOTHBRUSH => 'Suction Toothbrush',
            self::OTHER => 'Other',
        };
    }
}
