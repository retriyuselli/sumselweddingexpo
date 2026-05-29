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
            $hargaJual = (int) str_replace(',', '', $partisipasi->harga_jual);
            $diskon = (int) str_replace(',', '', $partisipasi->diskon);
            $partisipasi->harga_bersih = max(0, $hargaJual - $diskon);

            // Jika sedang update (sudah ada ID), hitung ulang status pembayaran
            // Ini untuk handle kasus edit harga/diskon yang mempengaruhi status lunas
            if ($partisipasi->exists) {
                $partisipasi->recalculatePaymentStatus();
            }
        });
    }

    public function recalculatePaymentStatus()
    {
        // Pastikan harga_bersih up to date (jika dipanggil manual sebelum saving)
        $hargaJual = (int) str_replace(',', '', $this->harga_jual);
        $diskon = (int) str_replace(',', '', $this->diskon);
        $this->harga_bersih = max(0, $hargaJual - $diskon);

        // Hitung total pembayaran dari relasi
        $total = $this->dataPembayarans()->sum('nominal');
        $this->total_pembayaran = $total;
        
        // Hitung sisa pembayaran
        $this->sisa_pembayaran = max(0, $this->harga_bersih - $total);

        // Tentukan status
        if ($total >= $this->harga_bersih && $this->harga_bersih > 0) {
            $this->status_pembayaran = 'Lunas';
        } else {
            $this->status_pembayaran = 'Belum Lunas';
        }
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
}
