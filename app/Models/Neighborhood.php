<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    protected $fillable = ['sub_district_id', 'rt', 'rw', 'postal_code'];

    public function subDistrict()
    {
        return $this->belongsTo(SubDistrict::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
