<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesHistory extends Model
{
    protected $table = 'sales_history';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'culture_id',
        'price',
        'weight',
        'sum',
        'date',
        'comment'
    ];
}
