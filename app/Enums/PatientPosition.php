<?php

declare(strict_types=1);

namespace App\Enums;

enum PatientPosition: string
{
    case SUPINE = 'supine';
    case SITTING = 'sitting';
    case STANDING = 'standing';
    case PRONE = 'prone';
    case LATERAL = 'lateral';

    public function label(): string
    {
        return match ($this) {
            self::SUPINE => 'Supine',
            self::SITTING => 'Sitting',
            self::STANDING => 'Standing',
            self::PRONE => 'Prone',
            self::LATERAL => 'Lateral',
        };
    }
}
