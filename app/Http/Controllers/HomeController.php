<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Sponsor;
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

        $eventDate = $expo?->tanggal_mulai?->format('Y-m-d H:i:s') ?? '2026-01-16 00:00:00';
        $eventStart = $expo?->tanggal_mulai;
        $eventEnd = $expo?->tanggal_selesai;
        $eventLocation = $expo?->lokasi ?? 'Palembang Icon';
        $eventName = $expo?->nama_expo ?? 'Wedding Expo 2026';

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
        ));
    }

    /**
     * @return list<array{date: Carbon, day_name: string, day_label: string, waktu: string, activity: string}>
     */
    private function buildScheduleDays(?Carbon $eventStart, ?Carbon $eventEnd, Collection $rundowns): array
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        if (! $eventStart || ! $eventEnd) {
            return [
                [
                    'date' => Carbon::parse('2026-01-16'),
                    'day_name' => 'Jumat',
                    'day_label' => 'Hari Pertama',
                    'waktu' => '10:00 – 21:00 WIB',
                    'activity' => 'Grand Opening & Talkshow',
                ],
                [
                    'date' => Carbon::parse('2026-01-17'),
                    'day_name' => 'Sabtu',
                    'day_label' => 'Hari Ke-2',
                    'waktu' => '10:00 – 21:00 WIB',
                    'activity' => 'Fashion Show & Live Music',
                ],
                [
                    'date' => Carbon::parse('2026-01-18'),
                    'day_name' => 'Minggu',
                    'day_label' => 'Hari Terakhir',
                    'waktu' => '10:00 – 21:00 WIB',
                    'activity' => 'Grand Doorprize',
                ],
            ];
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

            $fallbackActivity = $i === 0
                ? 'Grand Opening & Talkshow'
                : ($i === $duration - 1 ? 'Grand Doorprize' : 'Fashion Show & Live Music');

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
            return '10:00 – 21:00 WIB';
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

            return $raw === '' ? '10:00 – 21:00 WIB' : $raw.(str_contains(strtoupper($raw), 'WIB') ? '' : ' WIB');
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
