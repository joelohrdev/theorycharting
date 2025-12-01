<?php

declare(strict_types=1);

namespace App\Enums;

enum Mobility: string
{
    case NO = 'no';
    case X1QSHIFT = 'x1qshift';
    case X2QSHIFT = 'x2qshift';
    case X3QSHIFT = 'x3qshift';
    case X4QSHIFT = 'x4qshift';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::NO => 'No',
            self::X1QSHIFT => '1 Q Shift',
            self::X2QSHIFT => '2 Q Shift',
            self::X3QSHIFT => '3 Q Shift',
            self::X4QSHIFT => '4 Q Shift',
            self::OTHER => 'Other',
        };
    }
}
