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

    public static function getListForSelector()
    {
        $cultures = Culture::all();
        $data = [];
        foreach ($cultures as $culture){
            $data[] = [
              'name' => $culture->name,
              'number' => $culture->id
            ];
        }
        return $data;
    }

}
