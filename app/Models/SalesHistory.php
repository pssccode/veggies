<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function getDistinctYears($culture, $forSelect = false)
    {
        $items = SalesHistory::selectRaw("DATE_FORMAT(date, '%Y') as dt");
        if($culture > 0){
            $items->where('culture_id', $culture);
        }
        $result = $items->distinct(DB::raw("DATE_FORMAT(date, '%Y')"))->get();
        if($forSelect){
            $data = [];
            foreach ($result as $item){
                $data[] = [
                    'name' => $item->dt,
                    'number' => $item->dt
                ];
            }
            return $data;
        }
        return $result;
    }

    public static function getDistinctMonths($year, $culture)
    {

    }
}
