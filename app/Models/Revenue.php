<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'source', 'category', 'date', 'total'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'date' => 'datetime',
    ];
}
