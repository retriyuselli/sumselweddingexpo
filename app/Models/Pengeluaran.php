<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengeluaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expo_id',
        'nama_pengeluaran',
        'keterangan',
        'nominal',
        'tanggal',
        'rekening_tujuan_id',
        'rek_transfer',
        'nama_rekening_penerima',
        'bukti_transfer',
        'nota_dinas',
        'user_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function hasNotaDinas(): bool
    {
        return filled($this->nota_dinas);
    }

    public function hasBuktiTransfer(): bool
    {
        return filled($this->bukti_transfer);
    }

    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }

    public function rekeningTujuan()
    {
        return $this->belongsTo(RekeningTujuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
