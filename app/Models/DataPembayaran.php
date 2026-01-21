<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataPembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'partisipasi_id',
        'nama_pembayar',
        'nominal',
        'tanggal_bayar',
        'metode_pembayaran',
        'bukti_transfer',
        'rekening_tujuan_id',
        'termin_pembayaran',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    protected static function booted()
    {
        static::saved(function ($dataPembayaran) {
            $dataPembayaran->partisipasi->recalculatePaymentStatus();
            $dataPembayaran->partisipasi->saveQuietly();
        });

        static::deleted(function ($dataPembayaran) {
            $dataPembayaran->partisipasi->recalculatePaymentStatus();
            $dataPembayaran->partisipasi->saveQuietly();
        });
    }

    public function partisipasi()
    {
        return $this->belongsTo(Partisipasi::class);
    }

    public function rekeningTujuan()
    {
        return $this->belongsTo(RekeningTujuan::class);
    }
}
