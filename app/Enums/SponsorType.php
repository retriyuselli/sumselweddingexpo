<?php

namespace App\Enums;

enum SponsorType: string
{
    case CoSponsor = 'co_sponsor';
    case OfficialBankPartner = 'official_bank_partner';
    case MediaPartner = 'media_partner';
    case CommunityPartner = 'community_partner';
    case SupportingPartner = 'supporting_partner';

    public function label(): string
    {
        return match ($this) {
            self::CoSponsor => 'Co-Sponsor',
            self::OfficialBankPartner => 'Official Bank Partner',
            self::MediaPartner => 'Media Partner',
            self::CommunityPartner => 'Community Partner',
            self::SupportingPartner => 'Supporting Partner',
        };
    }

    public static function options(): array
    {
        return [
            self::CoSponsor->value => self::CoSponsor->label(),
            self::OfficialBankPartner->value => self::OfficialBankPartner->label(),
            self::MediaPartner->value => self::MediaPartner->label(),
            self::CommunityPartner->value => self::CommunityPartner->label(),
            self::SupportingPartner->value => self::SupportingPartner->label(),
        ];
    }
}