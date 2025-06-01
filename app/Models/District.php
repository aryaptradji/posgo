<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
     protected $fillable = ['name', 'slug', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function subDistricts()
    {
        return $this->hasMany(SubDistrict::class);
    }
}
