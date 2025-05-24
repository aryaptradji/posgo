<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    /** @use HasFactory<\Database\Factories\CourierFactory> */
    use HasFactory;

    protected $fillable = ['name', 'slug', 'phone', 'email', 'address'];

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
