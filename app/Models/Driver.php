<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'phone',
        'password',
        'vicheltype',
        'platenumber',
        'driverlicense',
        'pricemodel',
        'work_area',
        'image'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
