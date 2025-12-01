<?php

declare(strict_types=1);

namespace App\Enums;

enum Activity: string
{
    case UPADLIB = 'upadlib';
    case ASSISTX1 = 'assistx1';
    case ASSISTX2 = 'assistx2';
    case BATHROOMPRIVILAGES = 'bathroomprivileges';
    case UPTOCHAIR = 'uptochair';
    case BEDREST = 'bedrest';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::UPADLIB => 'Up Ad Lib',
            self::ASSISTX1 => 'Assist X1',
            self::ASSISTX2 => 'Assist X2',
            self::BATHROOMPRIVILAGES => 'Bathroom Privileges',
            self::UPTOCHAIR => 'Up to Chair',
            self::BEDREST => 'Bed Rest',
            self::OTHER => 'Other',
        };
    }
}
