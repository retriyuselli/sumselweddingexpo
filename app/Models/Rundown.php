<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Rundown extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Rundown {$eventName}");
    }
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
