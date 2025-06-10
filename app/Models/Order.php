<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'courier_id', 'time', 'shipped_at', 'arrived_at', 'code', 'category', 'payment_status', 'payment_method', 'shipping_status', 'photo', 'item', 'total', 'snap_token', 'snap_expires_at', 'snap_order_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function courier() {
        return $this->belongsTo(Courier::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function getShippedAtFormattedAttribute() {
        return $this->shipped_at ? $this->shipped_at->translatedFormat('d M Y H:i:s') : '-';
    }

    public function getArrivedAtFormattedAttribute() {
        return $this->arrived_at ? $this->arrived_at->translatedFormat('d M Y H:i:s') : '-';
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? $this->photo : null;
    }

    public function scopeBelumDibayar($query)
    {
        return $query->where('payment_status', 'belum dibayar');
    }

    public function scopeDikemas($query)
    {
        return $query->where('payment_status', 'dibayar')->where('shipping_status', 'belum dikirim');
    }

    public function scopeDikirim($query)
    {
        return $query->where('payment_status', 'dibayar')->where('shipping_status', 'dikirim');
    }

    public function scopeSelesai($query)
    {
        return $query->where('payment_status', 'dibayar')->where('shipping_status', 'selesai');
    }

    public function scopeBatal($query)
    {
        return $query->whereIn('payment_status', ['dibatalkan', 'kadaluwarsa', 'ditolak']);
    }

    protected $casts = [
        'time' => 'datetime',
        'snap_expires_at' => 'datetime',
        'shipped_at' => 'datetime',
        'arrived_at' => 'datetime'
    ];
}
