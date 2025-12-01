<?php

declare(strict_types=1);

namespace App\Enums;

enum StoolCharacteristics: string
{
    case SOFT = 'soft';
    case FORMED = 'formed';
    case BROWN = 'brown';
    case BLACK = 'black';
    case BLOODY = 'bloody';
    case CLAY = 'clay';
    case CLEAR = 'clear';
    case COLOREDWATER = 'coloredwater';
    case GREEN = 'green';
    case HARD = 'hard';
    case LIQUID = 'liquid';
    case LOOSE = 'loose';
    case MECONIUM = 'meconium';
    case MUCOUS = 'mucous';

    case NONFORMED = 'nonformed';
    case RED = 'red';
    case REDSTREAKS = 'redstreaks';
    case RESULTSOFENEMA = 'resultsofenema';
    case SEEDY = 'seedy';
    case SEROUS = 'serous';
    case TAN = 'tan';
    case YELLOW = 'yellow';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::SOFT => 'Soft',
            self::FORMED => 'Formed',
            self::BROWN => 'Brown',
            self::BLACK => 'Black',
            self::BLOODY => 'Bloody',
            self::CLAY => 'Clay',
            self::CLEAR => 'Clear',
            self::COLOREDWATER => 'Colored Water',
            self::GREEN => 'Green',
            self::HARD => 'Hard',
            self::LIQUID => 'Liquid',
            self::LOOSE => 'Loose',
            self::MECONIUM => 'Meconium',
            self::MUCOUS => 'Mucous',
            self::NONFORMED => 'Non-Formed',
            self::RED => 'Red',
            self::REDSTREAKS => 'Red Streaks',
            self::RESULTSOFENEMA => 'Results of Enema',
            self::SEEDY => 'Seedy',
            self::SEROUS => 'Serous',
            self::TAN => 'Tan',
            self::YELLOW => 'Yellow',
            self::OTHER => 'Other',
        };
    }
}
