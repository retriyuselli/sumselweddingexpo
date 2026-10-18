<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Expo extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Expo {$eventName}");
    }

    protected $fillable = [
        'nama_expo',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_buka_absensi',
        'jam_tutup_absensi',
        'lokasi',
        'alamat',
        'status',
        'periode',
        'visitor_prizes_enabled',
        'visitor_prizes',
        'visitor_prize_terms',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => 'boolean',
        'visitor_prizes_enabled' => 'boolean',
        'visitor_prizes' => 'array',
    ];

    public function categoryTenants()
    {
        return $this->hasMany(CategoryTenant::class);
    }

    public function partisipasis()
    {
        return $this->hasMany(Partisipasi::class);
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function rundowns()
    {
        return $this->hasMany(Rundown::class);
    }

    public function tenantSpots()
    {
        return $this->hasMany(TenantSpot::class);
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function absensiUrl(): string
    {
        return route('absensi.show', $this);
    }

    public function absensiQrSvg(): string
    {
        $renderer = new \BaconQrCode\Renderer\ImageRenderer(
            new \BaconQrCode\Renderer\RendererStyle\RendererStyle(280, 2),
            new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
        );

        return (new \BaconQrCode\Writer($renderer))->writeString($this->absensiUrl());
    }

    public function absensiQrPng(): string
    {
        $renderer = new \BaconQrCode\Renderer\GDLibRenderer(1024, 4);

        return (new \BaconQrCode\Writer($renderer))->writeString($this->absensiUrl());
    }

    public function absensiQrFilename(): string
    {
        $slug = \Illuminate\Support\Str::slug($this->nama_expo);

        return 'qr-absensi-'.($slug !== '' ? $slug : 'expo-'.$this->id).'.png';
    }

    public function isAbsensiOpen(?CarbonInterface $moment = null): bool
    {
        $moment = ($moment ?? now())->copy()->timezone('Asia/Jakarta');

        if (! $this->status || ! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return false;
        }

        $date = $moment->copy()->startOfDay();
        $inRange = $date->between(
            $this->tanggal_mulai->copy()->startOfDay(),
            $this->tanggal_selesai->copy()->startOfDay()
        );

        if (! $inRange) {
            return false;
        }

        $opens = $this->absensiOpenTime();
        $closes = $this->absensiCloseTime();
        if ($closes <= $opens) {
            return false;
        }

        $time = $moment->format('H:i:s');

        return $time >= $opens && $time <= $closes;
    }

    public function absensiHoursLabel(): string
    {
        return 'pukul '.$this->formatAbsensiClock($this->absensiOpenTime()).'–'.$this->formatAbsensiClock($this->absensiCloseTime()).' WIB';
    }

    public function absensiOpenTime(): string
    {
        return $this->normalizeAbsensiClock($this->jam_buka_absensi, '09:00:00');
    }

    public function absensiCloseTime(): string
    {
        return $this->normalizeAbsensiClock($this->jam_tutup_absensi, '22:00:00');
    }

    private function normalizeAbsensiClock(mixed $value, string $fallback): string
    {
        if ($value instanceof CarbonInterface) {
            return $value->format('H:i:s');
        }

        $value = trim((string) $value);
        if (preg_match('/^\d{2}:\d{2}$/', $value) === 1) {
            return $value.':00';
        }
        if (preg_match('/^\d{2}:\d{2}:\d{2}$/', $value) === 1) {
            return $value;
        }

        return $fallback;
    }

    private function formatAbsensiClock(string $time): string
    {
        return str_replace(':', '.', substr($time, 0, 5));
    }

    public function absensiClosedMessage(?CarbonInterface $moment = null): ?string
    {
        $moment = ($moment ?? now())->copy()->timezone('Asia/Jakarta');

        if ($this->isAbsensiOpen($moment)) {
            return null;
        }

        if (! $this->status) {
            return 'Absensi untuk expo ini belum aktif.';
        }

        $date = $moment->copy()->startOfDay();
        $hours = $this->absensiHoursLabel();

        if ($this->tanggal_mulai && $date->lt($this->tanggal_mulai->copy()->startOfDay())) {
            return 'Absensi dibuka pada '.$this->tanggal_mulai->locale('id')->translatedFormat('d F Y').', '.$hours.'.';
        }

        if ($this->tanggal_selesai && $date->gt($this->tanggal_selesai->copy()->startOfDay())) {
            return 'Absensi expo ini sudah ditutup pada '.$this->tanggal_selesai->locale('id')->translatedFormat('d F Y').'.';
        }

        return 'Absensi dibuka '.$hours.'.';
    }

    /**
     * Label ringkas untuk select/filter (nama · periode · tanggal).
     */
    public function labelForSelect(): string
    {
        $tanggal = null;
        if ($this->tanggal_mulai) {
            $tanggal = $this->tanggal_mulai->format('d M Y');
            if ($this->tanggal_selesai && ! $this->tanggal_mulai->equalTo($this->tanggal_selesai)) {
                $tanggal .= ' – '.$this->tanggal_selesai->format('d M Y');
            }
        }

        $parts = array_values(array_filter([
            $this->periode ? 'Periode '.$this->periode : null,
            $tanggal,
        ]));

        return $parts === []
            ? $this->nama_expo.' [#'.$this->id.']'
            : $this->nama_expo.' ('.implode(' · ', $parts).')';
    }

    public function labelDetails(): ?string
    {
        $tanggal = null;
        if ($this->tanggal_mulai) {
            $tanggal = $this->tanggal_mulai->format('d M Y');
            if ($this->tanggal_selesai && ! $this->tanggal_mulai->equalTo($this->tanggal_selesai)) {
                $tanggal .= ' – '.$this->tanggal_selesai->format('d M Y');
            }
        }

        $parts = array_values(array_filter([
            $this->periode ? 'Periode '.$this->periode : null,
            $tanggal,
        ]));

        return $parts === [] ? null : implode(' · ', $parts);
    }
}
