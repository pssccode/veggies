<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prediction extends Model
{
    protected $table = 'prediction';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'culture_id',
        'weight',
        'price',
        'date'
    ];
}
