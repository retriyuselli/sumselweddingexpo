<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengeluaranLain extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_pengeluaran',
        'keterangan',
        'nominal',
        'tanggal',
        'rekening_tujuan_id',
        'user_id',
        'bukti_transfer',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function rekeningTujuan()
    {
        return $this->belongsTo(RekeningTujuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
