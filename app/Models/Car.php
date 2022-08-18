<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'vin',
        'car_model_id',
        'fuel_type_id',
        'engine_power',
        'year',
        'distance'
    ];

}
