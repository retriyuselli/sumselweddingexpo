<?php

namespace App\Services;

use App\Models\Expo;
use Illuminate\Support\Facades\Cache;

class ExpoResolver
{
    public function nearestActive(?int $ttlSeconds = 300): ?Expo
    {
        return Cache::remember('expo.nearest_active', $ttlSeconds, function () {
            $expo = Expo::query()
                ->where('status', true)
                ->whereDate('tanggal_mulai', '>=', now()->toDateString())
                ->orderBy('tanggal_mulai', 'asc')
                ->first();

            if ($expo) {
                return $expo;
            }

            return Expo::query()
                ->where('status', true)
                ->orderBy('tanggal_mulai', 'desc')
                ->first();
        });
    }

    public static function forgetNearest(): void
    {
        Cache::forget('expo.nearest_active');
    }
}
