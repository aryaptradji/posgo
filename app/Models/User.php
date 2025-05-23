<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['created_at', 'name', 'email', 'phone_number', 'password', 'plaintext_password', 'role', 'address_id', 'photo'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'plaintext_password',
        // 'remember_token',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    protected $casts = [
        'created' => 'datetime',
    ];

    public function getVisiblePasswordAttribute()
    {
        return $this->role === 'cashier' ? $this->plaintext_password : null;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }
}
