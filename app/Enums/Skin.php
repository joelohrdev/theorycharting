<?php

declare(strict_types=1);

namespace App\Enums;

enum Skin: string
{
    case ASSESSED = 'assessed';
    case RNNOTIFIED = 'rnnotified';
    case LOTIONAPPLIED = 'lotionapplied';
    case BACKRUB = 'backrub';
    case MASSAGE = 'massage';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::ASSESSED => 'Assessed',
            self::RNNOTIFIED => 'RN Notified',
            self::LOTIONAPPLIED => 'Lotion Applied',
            self::BACKRUB => 'Backrub',
            self::MASSAGE => 'Massage',
            self::OTHER => 'Other',
        };
    }
}
