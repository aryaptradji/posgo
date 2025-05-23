<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'neighborhood_id', 'street', 'notes'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function neighborhood()
    {
        return $this->belongsTo(Neighborhood::class);
    }
}
