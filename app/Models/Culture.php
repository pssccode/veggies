<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    protected $table = 'cultures';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'img'
    ];
}
