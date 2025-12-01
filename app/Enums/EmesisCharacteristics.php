<?php

declare(strict_types=1);

namespace App\Enums;

enum EmesisCharacteristics: string
{
    case BILE = 'bile';
    case BLOODY = 'bloody';
    case BROWN = 'brown';
    case CLEAR = 'clear';
    case COFFEEGROUND = 'coffeeground';
    case PROJECTILE = 'projectile';
    case RED = 'red';
    case TAN = 'tan';
    case UNDIGESTEDFOOD = 'undigestedfood';
    case YELLOW = 'yellow';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::BILE => 'Bile',
            self::BLOODY => 'Bloody',
            self::BROWN => 'Brown',
            self::CLEAR => 'Clear',
            self::COFFEEGROUND => 'Coffee Ground',
            self::PROJECTILE => 'Projectile',
            self::RED => 'Red',
            self::TAN => 'Tan',
            self::UNDIGESTEDFOOD => 'Undigested Food',
            self::YELLOW => 'Yellow',
            self::OTHER => 'Other',
        };
    }
}
