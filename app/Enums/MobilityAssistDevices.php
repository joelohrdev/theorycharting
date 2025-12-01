<?php

declare(strict_types=1);

namespace App\Enums;

enum MobilityAssistDevices: string
{
    case NONE = 'none';
    case CANE = 'cane';
    case CRUTCHES = 'crutches';
    case FOURPOINTCANE = 'fourpointcane';
    case FRONTWHEELWALKER = 'frontwheelwalker';
    case WHEELCHAIR = 'wheelchair';
    case GAITBELT = 'gaitbelt';
    case TRANSFERMAT = 'transfermat';
    case SPLINTBRACE = 'splintbrace';
    case IMMOBILIZER = 'immobilizer';
    case ORTHOSHOE = 'orthoshoe';
    case ORTHOBOOT = 'orthoboot';
    case PROTHESIS = 'prothesis';
    case SITTOSTANDLIFT = 'sittostandlift';
    case CEILINGLIFT = 'ceilinglift';
    case SLIDEBOARD = 'slideboard';
    case TOTALLIFT = 'totallift';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'None',
            self::CANE => 'Cane',
            self::CRUTCHES => 'Crutches',
            self::FOURPOINTCANE => '4-Point Cane',
            self::FRONTWHEELWALKER => 'Front-Wheel Walker',
            self::WHEELCHAIR => 'Wheelchair',
            self::GAITBELT => 'Gait Belt',
            self::TRANSFERMAT => 'Transfer Mat/Pad/Sheet',
            self::SPLINTBRACE => 'Splint/Brace',
            self::IMMOBILIZER => 'Immobilizer',
            self::ORTHOSHOE => 'Orthopedic Shoe',
            self::ORTHOBOOT => 'Orthopedic Boot',
            self::PROTHESIS => 'Prothesis',
            self::SITTOSTANDLIFT => 'Sit-to-Stand Lift',
            self::CEILINGLIFT => 'Ceiling Lift',
            self::SLIDEBOARD => 'Slide Board/Sheet',
            self::TOTALLIFT => 'Total Lift',
            self::OTHER => 'Other',
        };
    }
}
