<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'product_vendor_id',
        'vendor_id',
        'name_snapshot',
        'price_snapshot',
        'qty',
        'subtotal',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductVendor::class, 'product_vendor_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}