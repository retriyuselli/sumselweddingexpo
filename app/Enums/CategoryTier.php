<?php

namespace App\Enums;

enum CategoryTier: string
{
    case Silver = 'silver';
    case Gold = 'gold';
    case Platinum = 'platinum';

    public function label(): string
    {
        return match ($this) {
            self::Silver => 'Silver',
            self::Gold => 'Gold',
            self::Platinum => 'Platinum',
        };
    }

    public static function options(): array
    {
        return [
            self::Silver->value => self::Silver->label(),
            self::Gold->value => self::Gold->label(),
            self::Platinum->value => self::Platinum->label(),
        ];
    }
}
