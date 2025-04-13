<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'pickup_location',
        'destination',
        'package_type',
        'driver_id',
        'delivery_type',
        'delivery_date',
        'special_instructions',
        'amount',
        'status'
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function driver()
{
    return $this->belongsTo(Driver::class);
}
}