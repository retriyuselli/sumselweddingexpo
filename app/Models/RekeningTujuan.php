<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekeningTujuan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'nama_pemilik',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function dataPembayarans()
    {
        return $this->hasMany(DataPembayaran::class);
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function pengeluaranLains()
    {
        return $this->hasMany(PengeluaranLain::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
