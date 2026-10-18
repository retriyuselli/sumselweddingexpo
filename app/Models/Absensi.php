<?php

namespace App\Models;

use App\Enums\TujuanKunjungan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'expo_id',
        'email',
        'no_tlp',
        'tujuan',
        'tujuan_lainnya',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tujuan' => TujuanKunjungan::class,
    ];

    public function expo(): BelongsTo
    {
        return $this->belongsTo(Expo::class);
    }

    public function tujuanTampilan(): string
    {
        $tujuan = $this->tujuan;

        if ($tujuan === TujuanKunjungan::Lainnya) {
            return filled($this->tujuan_lainnya) ? $this->tujuan_lainnya : $tujuan->label();
        }

        return $tujuan?->label() ?? '';
    }

    public static function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '62')) {
            $digits = '0'.substr($digits, 2);
        }

        return $digits;
    }
}
