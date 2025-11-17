<?php

namespace App\Enums;

enum CategoryTier: string
{
    case Silver = 'silver';
    case Gold = 'gold';

    public function label(): string
    {
        return match ($this) {
            self::Silver => 'Silver',
            self::Gold => 'Gold',
        };
    }

    public static function options(): array
    {
        return [
            self::Silver->value => self::Silver->label(),
            self::Gold->value => self::Gold->label(),
        ];
    }
}
