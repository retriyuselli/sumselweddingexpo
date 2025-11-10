<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisUsaha extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama_jenis_usaha',
    ];

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }
}
