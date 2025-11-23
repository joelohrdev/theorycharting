<?php

declare(strict_types=1);

namespace App\Enums;

enum FacePainScale: string
{
    case ONE = '1';
    case TWO = '2';
    case THREE = '3';
    case FOUR = '4';
    case FIVE = '5';

    public function label(): string
    {
        return match ($this) {
            self::ONE => '😊 No Pain',
            self::TWO => '😐 Mild',
            self::THREE => '☹️ Moderate',
            self::FOUR => '😠 Severe',
            self::FIVE => '😡 Worst',
        };
    }
}
