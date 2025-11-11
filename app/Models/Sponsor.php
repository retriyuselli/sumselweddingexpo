<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'website',
        'description',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Home (jika diperlukan)
    public function home()
    {
        return $this->belongsToMany(Home::class, 'home_sponsor');
    }
}
