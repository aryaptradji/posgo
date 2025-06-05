<?php

namespace App\Models;

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

    public function scopeBelumDibayar($query)
    {
        return $query->where('payment_status', 'belum dibayar')->where('shipping_status', 'belum dikirim');
    }

    public function scopeDikemas($query) {
        return $query->where('payment_status', 'dibayar')->where('shipping_status', 'belum dikirim');
    }

    public function scopeDikirim($query) {
        return $query->where('payment_status', 'dibayar')->where('shipping_status', 'dalam perjalanan');
    }

    public function scopeSelesai($query) {
        return $query->where('payment_status', 'dibayar')->where('shipping_status', 'selesai');
    }

    public function scopeBatal($query) {
        return $query->whereIn('payment_status', ['dibatalkan', 'kadaluwarsa', 'ditolak']);
    }

    protected $casts = [
        'time' => 'datetime',
    ];
}
