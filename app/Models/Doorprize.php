<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Doorprize extends Model
{
    use LogsActivity;

    public const MIN_NOMINAL_TRANSAKSI = 2_000_000;

    protected $fillable = [
        'name',
        'kodevoucher',
        'no_wa',
        'email',
        'nik',
        'alamat',
        'provinsi',
        'partisipasi_id',
        'foto_ktp',
        'surat_pernyataan',
        'sudah_download_tring',
        'transactions',
    ];

    protected $casts = [
        'sudah_download_tring' => 'boolean',
        'transactions' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Doorprize {$eventName}");
    }

    public function partisipasi()
    {
        return $this->belongsTo(Partisipasi::class);
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $transactions
     */
    public static function parseMoney(mixed $value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_int($value) || is_float($value)) {
            return (int) $value;
        }

        return (int) str_replace([',', ' '], '', (string) $value);
    }

    /**
     * @param  array<int, array<string, mixed>>|Collection<int, array<string, mixed>>|null  $transactions
     */
    public static function sumTransactionField(array|Collection|null $transactions, string $field): int
    {
        return collect($transactions ?? [])
            ->sum(fn ($item) => self::parseMoney(is_array($item) ? ($item[$field] ?? 0) : 0));
    }

    public function getTotalNominalTransaksiAttribute(): int
    {
        return self::sumTransactionField($this->transactions, 'nom_trx');
    }

    public function getTotalNominalRevenueAttribute(): int
    {
        return self::sumTransactionField($this->transactions, 'no_rev');
    }

    public static function formatRupiah(int $amount): string
    {
        return 'Rp '.number_format($amount, 0, ',', '.');
    }

    public function hasFotoKtp(): bool
    {
        return filled($this->foto_ktp);
    }
}
