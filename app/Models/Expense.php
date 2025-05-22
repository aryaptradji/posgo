<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    protected $fillable = ['source', 'category', 'date', 'total'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $casts = [
        'date' => 'datetime',
    ];
}
