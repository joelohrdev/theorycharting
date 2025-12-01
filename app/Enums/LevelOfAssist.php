<?php

declare(strict_types=1);

namespace App\Enums;

enum LevelOfAssist: string
{
    case INDEPENDENT = 'independent';
    case ASSISTED = 'assisted';
    case COMPLETE = 'complete';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::INDEPENDENT => 'Independent',
            self::ASSISTED => 'Assisted',
            self::COMPLETE => 'Complete',
            self::OTHER => 'Other',
        };
    }
}
