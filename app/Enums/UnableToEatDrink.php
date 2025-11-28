<?php

declare(strict_types=1);

namespace App\Enums;

enum UnableToEatDrink: string
{
    case ALTEREDMENTALSTATE = 'alteredmentalstate';
    case INTUBATED = 'intubated';
    case NAUSEA = 'nausea';
    case NPO = 'npo';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ALTEREDMENTALSTATE => 'Altered Mental State',
            self::INTUBATED => 'Intubated',
            self::NAUSEA => 'Nausea',
            self::NPO => 'NPO',
            self::OTHER => 'Other',
        };
    }
}
