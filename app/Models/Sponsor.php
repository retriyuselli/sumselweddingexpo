<?php

namespace App\Models;

use App\Enums\SponsorType;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'expo_id',
        'name',
        'logo',
        'website',
        'description',
        'jenis_sponsor',
        'is_active',
        'order',
        'bantuan',
        'nominal',
        'kewajiban',
    ];

    protected $casts = [
        'jenis_sponsor' => SponsorType::class,
        'is_active' => 'boolean',
        'bantuan' => 'array',
        'kewajiban' => 'array',
    ];

    // Relasi ke Home (jika diperlukan)
    public function home()
    {
        return $this->belongsToMany(Home::class, 'home_sponsor');
    }

    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }

    // Accessor untuk mendapatkan hanya nama file dari logo (jika ada prefix path)
    public function getLogoFileNameAttribute()
    {
        if (! $this->logo) {
            return null;
        }

        // Jika logo mengandung '/', ambil hanya bagian setelah '/sponsors/'
        if (strpos($this->logo, '/') !== false) {
            return basename($this->logo);
        }

        return $this->logo;
    }
}
