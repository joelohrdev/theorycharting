<?php

declare(strict_types=1);

namespace App\Enums;

enum RangeOfMotion: string
{
    case AROM = 'arom';
    case AAROM = 'aarom';
    case PROMALLEXTREMITIES = 'promalextremities';
    case UPPEREXTREMITIES = 'uppextremities';
    case LOWEREXTREMITIES = 'lowextremities';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::AROM => 'AROM',
            self::AAROM => 'AAROM',
            self::PROMALLEXTREMITIES => 'PROM All Extremities',
            self::UPPEREXTREMITIES => 'Upper Extremities',
            self::LOWEREXTREMITIES => 'Lower Extremities',
            self::OTHER => 'Other',
        };
    }
}
