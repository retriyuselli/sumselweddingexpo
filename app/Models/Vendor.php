<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Vendor extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Vendor {$eventName}");
    }

    protected $fillable = [
        'user_id',
        'nama_pendaftar',
        'nama_vendor',
        'slug',
        'jenis_usaha_id',
        'alamat',
        'kota',
        'no_telepon',
        'email',
        'nama_pic',
        'no_wa_pic',
        'logo',
    ];

    public function jenisUsaha()
    {
        return $this->belongsTo(JenisUsaha::class);
    }

    public function partisipasis()
    {
        return $this->hasMany(Partisipasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(ProductVendor::class);
    }

    /**
     * Latest / preferred partisipasi for a given expo (or most recent overall).
     */
    public function partisipasiForExpo(?int $expoId = null): ?Partisipasi
    {
        $query = $this->partisipasis()->with(['categoryTenant', 'tenantSpot', 'expo']);

        if ($expoId) {
            return $query->where('expo_id', $expoId)->latest('id')->first();
        }

        return $query->latest('id')->first();
    }

    public function getWhatsappNumberAttribute()
    {
        $number = preg_replace('/[^0-9]/', '', $this->no_wa_pic);

        if (substr($number, 0, 1) === '0') {
            $number = '62'.substr($number, 1);
        }

        return $number;
    }
}
