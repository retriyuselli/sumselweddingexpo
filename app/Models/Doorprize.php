<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doorprize extends Model
{
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
