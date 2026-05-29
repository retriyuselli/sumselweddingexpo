<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantSpot extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expo_id',
        'kode_booth',
        'blok',
        'nomor',
        'section',
        'baris',
        'kolom',
    ];

    protected $casts = [
        'nomor' => 'integer',
        'baris' => 'integer',
        'kolom' => 'integer',
    ];

    // ─── Relationships ───────────────────────────────────────────

    public function expo(): BelongsTo
    {
        return $this->belongsTo(Expo::class);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    /** Display-friendly code: "A-01" → "A1", "B-11" → "B11" */
    public function getDisplayCodeAttribute(): string
    {
        return preg_replace('/^([A-Z])-0?(\d+)$/', '$1$2', $this->kode_booth);
    }

    /** Build kode_booth string from blok + nomor */
    public static function buildKode(string $blok, int $nomor): string
    {
        return strtoupper($blok) . '-' . str_pad($nomor, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a batch of TenantSpot records for an expo block.
     *
     * For Blok A (cols=2, rows=5, fill column-first):
     *   TenantSpot::generateBatch($expo->id, 'A', 1, 10, cols: 2, fillByCol: true)
     *
     * For Blok B left section (cols=5, rows=2):
     *   TenantSpot::generateBatch($expo->id, 'B', 1, 10, cols: 5, section: 'kiri')
     *
     * For Blok C (single row):
     *   TenantSpot::generateBatch($expo->id, 'C', 1, 10, cols: 10)
     */
    public static function generateBatch(
        int    $expoId,
        string $blok,
        int    $from,
        int    $to,
        int    $cols = 5,
        bool   $fillByCol = false,
        ?string $section = null,
    ): void {
        $rows = (int) ceil(($to - $from + 1) / $cols);

        for ($n = $from; $n <= $to; $n++) {
            $offset = $n - $from; // 0-based

            if ($fillByCol) {
                // Column-first fill (used for Blok A)
                $kolom = (int) floor($offset / $rows) + 1;
                $baris = ($offset % $rows) + 1;
            } else {
                // Row-first fill (default)
                $baris = (int) floor($offset / $cols) + 1;
                $kolom = ($offset % $cols) + 1;
            }

            static::firstOrCreate(
                ['expo_id' => $expoId, 'kode_booth' => static::buildKode($blok, $n)],
                [
                    'blok'    => strtoupper($blok),
                    'nomor'   => $n,
                    'section' => $section,
                    'baris'   => $baris,
                    'kolom'   => $kolom,
                ],
            );
        }
    }
}
