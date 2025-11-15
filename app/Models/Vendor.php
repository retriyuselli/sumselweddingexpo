<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nama_pendaftar',
        'nama_vendor',
        'slug',
        'jenis_usaha_id',
        'alamat',
        'kota',
        'no_telepon',
        'pendamping_tenant',
        'email',
        'nama_pic',
        'no_wa_pic',
        'paket',
        'lokasi_booth',
        'harga_jual',
    ];

    public function jenisUsaha()
    {
        return $this->belongsTo(JenisUsaha::class);
    }

    public function partisipasis()
    {
        return $this->hasMany(Partisipasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(ProductVendor::class);
    }
}
