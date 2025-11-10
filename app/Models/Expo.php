<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_expo',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'status',
        'periode',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => 'boolean',
    ];

    public function categoryTenants()
    {
        return $this->hasMany(CategoryTenant::class);
    }

    public function partisipasis()
    {
        return $this->hasMany(Partisipasi::class);
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
