<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    protected $fillable = [
        'expo_id',
        'email',
        'no_tlp',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function expo(): BelongsTo
    {
        return $this->belongsTo(Expo::class);
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
