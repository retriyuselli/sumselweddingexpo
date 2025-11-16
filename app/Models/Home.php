<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = [
        'tentang_kami',
        'highlight_videos',
        'hero_subtitle',
        'meta_description',
        'hero_bg_image',
        'hero_side_image',
        'hero_bg_image_mobile',
        'penyelenggara_id',
        'is_active',
    ];

    protected $casts = [
        'highlight_videos' => 'json',
        'is_active' => 'boolean',
    ];

    // Relasi ke Sponsor (Many-to-Many jika perlu)
    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class, 'home_sponsor')
            ->orderBy('order')
            ->where('is_active', true);
    }

    public function penyelenggara()
    {
        return $this->belongsTo(Penyelenggara::class);
    }

    // Scope untuk mendapatkan data home yang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Static method untuk mendapatkan home data
    public static function getHome()
    {
        return self::first() ?? new self;
    }
}
