<?php

declare(strict_types=1);

namespace App\Enums;

enum UrineCharacteristics: string
{
    case CLEAR = 'clear';
    case YELLOW = 'yellow';
    case NOODOR = 'noodor';
    case AMBER = 'amber';
    case CLOTS = 'clots';
    case CLOUDY = 'cloudy';
    case CONCENTRATED = 'concentrated';
    case DILUTED = 'diluted';
    case BLOODY = 'bloody';
    case CHERRY = 'cherry';
    case ORANGE = 'orange';
    case PINKTINGED = 'pinktinged';
    case PURULENT = 'purulent';
    case TEA = 'tea';
    case MUCOUSTHREADS = 'mucousthreads';
    case SEDIMENT = 'sediment';
    case FOULODER = 'fouloder';
    case FRUITYODOR = 'fruityodor';
    case BROWN = 'brown';
    case RED = 'red';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::CLEAR => 'Clear',
            self::YELLOW => 'Yellow',
            self::NOODOR => 'No Odor',
            self::AMBER => 'Amber',
            self::CLOTS => 'Clots',
            self::CLOUDY => 'Cloudy',
            self::CONCENTRATED => 'Concentrated',
            self::DILUTED => 'Diluted',
            self::BLOODY => 'Bloody',
            self::CHERRY => 'Cherry',
            self::ORANGE => 'Orange',
            self::PINKTINGED => 'Pink Tinged',
            self::PURULENT => 'Purulent',
            self::TEA => 'Tea',
            self::MUCOUSTHREADS => 'Mucous Threads',
            self::SEDIMENT => 'Sediment',
            self::FOULODER => 'Foul Oder',
            self::FRUITYODOR => 'Fruity Odor',
            self::BROWN => 'Brown',
            self::RED => 'Red',
            self::OTHER => 'Other',
        };
    }
}
