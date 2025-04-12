<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'password',
        'image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}