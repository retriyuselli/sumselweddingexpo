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
        'nota_dinas',
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

    public function rekeningTujuan()
    {
        return $this->belongsTo(RekeningTujuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
