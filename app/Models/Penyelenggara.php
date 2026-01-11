<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penyelenggara extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'logo',
        'favicon',
        'banner',
        'banner_2',
        'alamat',
        'tentang',
        'jam_operasional',
        'email',
        'no_tlp',
        'instagram',
        'tiktok',
    ];

    public function galleries()
    {
        return $this->hasMany(PenyelenggaraGallery::class);
    }
}
