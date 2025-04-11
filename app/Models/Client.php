<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
   public $fillable= [
        'fname',
        'lname',
        'email',
        'phone',
        'password',
        'image'
    ];

    protected $hidden = [
        'password',
    ];
}
