<?php

namespace App\Models;

use Carbon\Carbon;
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
        if ($culture > 0) {
            $items->where('culture_id', $culture);
        }
        $result = $items->distinct(DB::raw("DATE_FORMAT(date, '%Y')"))->get();
        if ($forSelect) {
            $data = [];
            foreach ($result as $item) {
                $data[] = [
                    'name' => $item->dt,
                    'number' => $item->dt
                ];
            }
            return $data;
        }
        return $result;
    }

    public static function getDistinctMonths($year, $culture, $forSelect = false)
    {
        $items = SalesHistory::selectRaw("DATE_FORMAT(date, '%m') as dt");
        if ($culture > 0) {
            $items->where('culture_id', $culture);
        }
        if ($year > 0) {
            $items->whereRaw("DATE_FORMAT(date, '%Y') = " . $year);
        }
        $result = $items->distinct(DB::raw("DATE_FORMAT(date, '%m')"))->orderBy('date', 'asc')->get();
        if ($forSelect) {
            $data = [];
            foreach ($result as $item) {
                $data[] = [
                    'name' => SalesHistory::getMonthName((int)$item->dt),
                    'number' => $item->dt
                ];
            }
            return $data;
        }
        return $result;
    }

    public static function getMonthName($number)
    {
        $months = [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь',
        ];
        return $months[$number];
    }

    public static function getInfoForCharts($cultureId, $year, $month, $dates)
    {
        $sales = SalesHistory::query();

        if(isset($dates['startDate']) && isset($dates['endDate'])){
            $startDate = Carbon::parse($dates['startDate'])->startOfDay()->toDateTimeString();
            $endDate = Carbon::parse($dates['endDate'])->endOfDay()->toDateTimeString();
            $sales->whereBetween('date', [$startDate, $endDate]);
        }else{
            if($year > 0){
                $sales->whereRaw("DATE_FORMAT(date, '%Y') = ".$year);
            }
            if($month > 0){
                $sales->whereRaw("DATE_FORMAT(date, '%m') = ".$month);
            }
        }

        if($cultureId > 0){
            $sales->where('culture_id', $cultureId);
        }

        $sales = $sales->orderBy('date', 'asc')->get()->toArray();
        $results = [
            'dates' => [],
            'prices' => [],
            'weights' => [],
            'sum' => []
        ];

        foreach ($sales as $sale){
            if(isset($results['dates'][$sale['date']])){
                $results['prices'][$sale['date']] = $sale['price']; // TO-DO подумать над этим
                $results['weights'][$sale['date']] += $sale['weight'];
                $results['sum'][$sale['date']] += $sale['sum'];
            }else{
                $results['dates'][$sale['date']] = Carbon::parse($sale['date'])->format('d.m');
                $results['prices'][$sale['date']] = $sale['price'];
                $results['weights'][$sale['date']] = $sale['weight'];
                $results['sum'][$sale['date']] = $sale['sum'];
            }
        }

        $results['dates'] = array_values($results['dates']);
        $results['prices'] = array_values($results['prices']);
        $results['weights'] = array_values($results['weights']);
        $results['sum'] = array_values($results['sum']);

        return $results;
    }
}
