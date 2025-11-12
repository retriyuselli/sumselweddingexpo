<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PenyelenggaraGallery;

class Penyelenggara extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
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
