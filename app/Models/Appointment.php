<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'vendor_id',
        'expo_id',
        'starts_at',
        'ends_at',
        'duration_minutes',
        'location_type',
        'location_detail',
        'subject',
        'notes',
        'attendee_count',
        'preferred_contact',
        'contact_number',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function expo()
    {
        return $this->belongsTo(Expo::class);
    }
}