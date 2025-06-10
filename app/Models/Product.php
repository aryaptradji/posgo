<?php

namespace App\Models;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image', 'stock', 'pcs', 'price'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }
}
