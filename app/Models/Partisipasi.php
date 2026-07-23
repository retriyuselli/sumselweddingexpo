<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Partisipasi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Partisipasi {$eventName}");
    }

    protected $fillable = [
        'expo_id',
        'vendor_id',
        'vendor_pendamping',
        'tanggal_booking',
        'status_pembayaran',
        'is_active',
        'category_tenant_id',
        'tenant_spot_id',
        'blok_tenant',
        'harga_jual',
        'diskon',
        'harga_bersih',
        'total_pembayaran',
        'sisa_pembayaran',
        'keterangan',
        'is_barter',
        'barter_description',
        'barter_nominal',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'vendor_pendamping' => 'array',
        'is_active' => 'boolean',
        'harga_jual' => 'integer',
        'diskon' => 'integer',
        'harga_bersih' => 'integer',
        'total_pembayaran' => 'integer',
        'sisa_pembayaran' => 'integer',
        'is_barter' => 'boolean',
        'barter_nominal' => 'integer',
    ];

    protected static function booted()
    {
        static::saving(function ($partisipasi) {
            $partisipasi->harga_bersih = $partisipasi->hitungHargaBersih();

            // Jika sedang update (sudah ada ID), hitung ulang status pembayaran
            // Ini untuk handle kasus edit harga/diskon/barter yang mempengaruhi status lunas
            if ($partisipasi->exists) {
                $partisipasi->recalculatePaymentStatus();
            }
        });
    }

    /**
     * Harga bersih = harga jual - diskon - barter (jika ada).
     * Barter mengurangi kewajiban pembayaran tunai ke perusahaan.
     */
    public function hitungHargaBersih(): int
    {
        $hargaJual = (int) str_replace(',', '', $this->harga_jual ?? 0);
        $diskon = (int) str_replace(',', '', $this->diskon ?? 0);
        $barter = $this->is_barter ? (int) ($this->barter_nominal ?? 0) : 0;

        return max(0, $hargaJual - $diskon - $barter);
    }

    /**
     * Tagihan tunai ke perusahaan (sama dengan harga bersih).
     */
    public function tagihanTunai(): int
    {
        return $this->hitungHargaBersih();
    }

    /**
     * Status pembayaran berdasarkan tagihan tunai (harga jual - diskon - barter).
     */
    public function statusPembayaranEfektif(?int $totalDibayar = null): string
    {
        $tagihan = $this->hitungHargaBersih();
        $dibayar = $totalDibayar;

        if ($dibayar === null) {
            $dibayar = $this->relationLoaded('dataPembayarans')
                ? (int) $this->dataPembayarans->sum('nominal')
                : (int) ($this->total_pembayaran ?? 0);
        }

        return ($tagihan === 0 || $dibayar >= $tagihan) ? 'Lunas' : 'Belum Lunas';
    }

    public function recalculatePaymentStatus()
    {
        $this->harga_bersih = $this->hitungHargaBersih();

        // Hitung total pembayaran dari relasi
        $total = $this->dataPembayarans()->sum('nominal');
        $this->total_pembayaran = $total;

        // Hitung sisa pembayaran terhadap harga bersih (sudah termasuk potongan barter)
        $this->sisa_pembayaran = max(0, $this->harga_bersih - $total);

        // Tentukan status (lunas jika kewajiban tunai terpenuhi, termasuk full barter)
        $this->status_pembayaran = $this->statusPembayaranEfektif((int) $total);
    }


    public function expo(): BelongsTo
    {
        return $this->belongsTo(Expo::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categoryTenant(): BelongsTo
    {
        return $this->belongsTo(CategoryTenant::class);
    }

    public function tenantSpot(): BelongsTo
    {
        return $this->belongsTo(TenantSpot::class);
    }

    public function dataPembayarans()
    {
        return $this->hasMany(DataPembayaran::class);
    }

    public function doorprizes()
    {
        return $this->hasMany(Doorprize::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
