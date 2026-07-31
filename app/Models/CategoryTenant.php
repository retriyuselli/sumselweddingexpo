<?php

namespace App\Models;

use App\Enums\CategoryTier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryTenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expo_id',
        'category',
        'harga_jual',
        'harga_modal',
        'jumlah_unit',
        'ukuran',
        'deskripsi',
        'status',
    ];

    protected $casts = [
        'category' => CategoryTier::class,
    ];

    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }

    public function partisipasis()
    {
        return $this->hasMany(Partisipasi::class);
    }
}
