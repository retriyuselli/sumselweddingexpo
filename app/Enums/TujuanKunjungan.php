<?php

namespace App\Enums;

enum TujuanKunjungan: string
{
    case MencariVendor = 'mencari_vendor';
    case MencariVenue = 'mencari_venue';
    case MelihatPromo = 'melihat_promo';
    case Konsultasi = 'konsultasi';
    case Menemani = 'menemani';
    case MengikutiUndian = 'mengikuti_undian';
    case Lainnya = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::MencariVendor => 'Mencari vendor pernikahan',
            self::MencariVenue => 'Mencari venue',
            self::MelihatPromo => 'Melihat promo dan diskon',
            self::Konsultasi => 'Konsultasi perencanaan pernikahan',
            self::Menemani => 'Menemani calon pengantin',
            self::MengikutiUndian => 'Mengikuti undian',
            self::Lainnya => 'Lainnya',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            if ($case === self::Lainnya) {
                continue;
            }

            $options[$case->value] = $case->label();
        }

        $options[self::Lainnya->value] = self::Lainnya->label();

        return $options;
    }
}
