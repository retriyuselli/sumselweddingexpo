<?php

namespace App\Enums;

enum CategoryTier: string
{
    case Platinum = 'platinum';
    case Gold = 'gold';

    public function label(): string
    {
        return match ($this) {
            self::Platinum => 'Platinum',
            self::Gold => 'Gold',
        };
    }

    public static function options(): array
    {
        return [
            self::Platinum->value => self::Platinum->label(),
            self::Gold->value => self::Gold->label(),
        ];
    }
}
