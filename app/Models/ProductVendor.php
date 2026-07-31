<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'nama_produk',
        'slug',
        'harga',
        'dp_fixed',
        'deskripsi',
        'foto_url',
        'stok',
        'is_active',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'dp_fixed' => 'integer',
        'is_active' => 'boolean',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}