<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Sponsor;
use App\Models\Vendor;
use App\Services\ExpoResolver;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index(ExpoResolver $expoResolver)
    {
        $expo = $expoResolver->nearestActive();

        if ($expo) {
            $expo->load([
                'rundowns' => fn ($q) => $q->orderBy('tanggal')->orderBy('waktu'),
            ]);
        }

        $eventDate = $expo?->tanggal_mulai?->copy()->timezone('Asia/Jakarta')->startOfDay()->toIso8601String();
        $eventStart = $expo?->tanggal_mulai;
        $eventEnd = $expo?->tanggal_selesai;
        $eventLocation = $expo?->lokasi;
        $eventName = $expo?->nama_expo ?? 'Sumsel Wedding Expo';

        $scheduleDays = $this->buildScheduleDays(
            $eventStart,
            $eventEnd,
            $expo?->rundowns ?? collect(),
        );

        $home = Home::active()->with('penyelenggara')->first();
        if (! $home) {
            $home = new Home([
                'tentang_kami' => 'Selamat datang di Sumatra Selatan Wedding Expo',
                'hero_subtitle' => 'Temukan Vendor Pernikahan Impian Anda',
                'highlight_videos' => [],
                'is_active' => true,
            ]);
        }

        $sponsors = Sponsor::where('is_active', true)
            ->whereNotNull('logo')
            ->where('logo', '!=', '')
            ->orderBy('order')
            ->get();

        $penyelenggara = $home->penyelenggara;

        $participantQuery = Vendor::query()
            ->whereHas('partisipasis', fn ($query) => $query
                ->where('expo_id', $expo?->id)
                ->where('is_active', true));
        $participantCount = $expo ? (clone $participantQuery)->count() : 0;
        $featuredVendors = $expo
            ? $participantQuery->with('jenisUsaha')->orderBy('nama_vendor')->limit(6)->get()
            : collect();

        return view('home', compact(
            'eventDate',
            'eventStart',
            'eventEnd',
            'eventLocation',
            'eventName',
            'expo',
            'home',
            'sponsors',
            'penyelenggara',
            'scheduleDays',
            'participantCount',
            'featuredVendors',
        ));
    }

    /**
     * @return list<array{date: Carbon, day_name: string, day_label: string, waktu: string, activity: string}>
     */
    private function buildScheduleDays(?Carbon $eventStart, ?Carbon $eventEnd, Collection $rundowns): array
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        if (! $eventStart || ! $eventEnd) {
            return [];
        }

        $duration = (int) $eventStart->diffInDays($eventEnd) + 1;
        $scheduleDays = [];

        for ($i = 0; $i < $duration; $i++) {
            $currentDate = $eventStart->copy()->addDays($i);
            $dayRundowns = $rundowns
                ->filter(fn ($rundown) => $rundown->tanggal?->isSameDay($currentDate))
                ->values();

            $dayLabel = $i === 0
                ? 'Hari Pertama'
                : ($i === $duration - 1 ? 'Hari Terakhir' : 'Hari Ke-'.($i + 1));

            $fallbackActivity = 'Detail kegiatan segera diumumkan';

            $scheduleDays[] = [
                'date' => $currentDate,
                'day_name' => $days[$currentDate->dayOfWeek],
                'day_label' => $dayLabel,
                'waktu' => $this->resolveDayWaktu($dayRundowns),
                'activity' => $this->resolveDayActivity($dayRundowns, $fallbackActivity),
            ];
        }

        return $scheduleDays;
    }

    private function resolveDayWaktu(Collection $dayRundowns): string
    {
        if ($dayRundowns->isEmpty()) {
            return 'Jam segera diumumkan';
        }

        $starts = [];
        $ends = [];

        foreach ($dayRundowns as $rundown) {
            if (! preg_match_all('/(\d{1,2}:\d{2})/', (string) $rundown->waktu, $matches)) {
                continue;
            }

            $starts[] = $matches[1][0];
            $ends[] = $matches[1][count($matches[1]) - 1];
        }

        if ($starts === []) {
            $raw = trim((string) $dayRundowns->first()->waktu);

            return $raw === '' ? 'Jam segera diumumkan' : $raw.(str_contains(strtoupper($raw), 'WIB') ? '' : ' WIB');
        }

        sort($starts);
        sort($ends);

        return $starts[0].' – '.$ends[count($ends) - 1].' WIB';
    }

    private function resolveDayActivity(Collection $dayRundowns, string $fallback): string
    {
        if ($dayRundowns->isEmpty()) {
            return $fallback;
        }

        $acaras = $dayRundowns
            ->pluck('acara')
            ->filter()
            ->unique()
            ->values();

        if ($acaras->isEmpty()) {
            return $fallback;
        }

        if ($acaras->count() === 1) {
            return $acaras->first();
        }

        return $acaras->take(3)->implode(' · ').($acaras->count() > 3 ? '…' : '');
    }
}
