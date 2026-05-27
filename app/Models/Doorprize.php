<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Doorprize extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Doorprize {$eventName}");
    }
    protected $fillable = [
        'name',
        'kodevoucher',
        'no_wa',
        'email',
        'nik',
        'alamat',
        'provinsi',
        'partisipasi_id',
        'foto_ktp',
        'surat_penyataan',
        'sudah_download_tring',
        'transactions',
    ];

    protected $casts = [
        'sudah_download_tring' => 'boolean',
        'transactions' => 'array',
    ];

    public function partisipasi()
    {
        return $this->belongsTo(Partisipasi::class);
    }
}
