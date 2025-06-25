<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'code',
        'created',
        'status',
        'item',
        'subtotal',
        'total',
        'ppn_percentage',
        'photo'
    ];

    protected $casts = [
        'created' => 'datetime'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getRouteKeyName()
    {
        return 'code';
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? $this->photo : null;
    }
}
