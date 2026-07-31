<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebhookEvent extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider',
        'event_type',
        'external_id',
        'signature_valid',
        'processed',
        'payload',
        'processed_at',
    ];

    protected $casts = [
        'signature_valid' => 'boolean',
        'processed' => 'boolean',
        'payload' => 'array',
        'processed_at' => 'datetime',
    ];
}