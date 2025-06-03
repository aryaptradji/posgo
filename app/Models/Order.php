<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\ShippingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'time', 'code', 'category', 'payment_status', 'shipping_status', 'item', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    protected $casts = [
        'time' => 'datetime',
        'payment_status' => PaymentStatus::class,
        'shipping_status' => ShippingStatus::class,
    ];
}
