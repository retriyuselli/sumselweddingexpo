<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Expo extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Expo {$eventName}");
    }

    protected $fillable = [
        'nama_expo',
        'tanggal_mulai',
        'tanggal_selesai',
        'lokasi',
        'alamat',
        'status',
        'periode',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => 'boolean',
    ];

    public function categoryTenants()
    {
        return $this->hasMany(CategoryTenant::class);
    }

    public function partisipasis()
    {
        return $this->hasMany(Partisipasi::class);
    }

    public function sponsors()
    {
        return $this->hasMany(Sponsor::class);
    }

    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function rundowns()
    {
        return $this->hasMany(Rundown::class);
    }
}
