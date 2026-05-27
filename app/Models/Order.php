<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Order extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Order {$eventName}");
    }

    protected $fillable = [
        'customer_id',
        'code',
        'amount_subtotal',
        'amount_total',
        'status',
        'billing_first_name',
        'billing_last_name',
        'billing_company',
        'billing_country',
        'billing_street',
        'billing_apt',
        'billing_city',
        'billing_province',
        'billing_postcode',
        'billing_phone',
        'billing_email',
        'notes',
    ];

    protected $casts = [
        'amount_subtotal' => 'decimal:2',
        'amount_total' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}