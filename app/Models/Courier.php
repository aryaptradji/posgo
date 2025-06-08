<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    /** @use HasFactory<\Database\Factories\CourierFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'phone', 'email'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
