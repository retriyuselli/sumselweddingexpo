<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partisipasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expo_id',
        'vendor_id',
        'vendor_pendamping',
        'tanggal_booking',
        'status_pembayaran',
        'category_tenant_id',
        'blok_tenant',
        'harga_jual',
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'vendor_pendamping' => 'array',
    ];

    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function categoryTenant()
    {
        return $this->belongsTo(CategoryTenant::class);
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
