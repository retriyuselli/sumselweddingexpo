<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rundown extends Model
{
    protected $fillable = [
        'expo_id',
        'tanggal',
        'waktu',
        'acara',
        'deskripsi',
        'lokasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }
}
