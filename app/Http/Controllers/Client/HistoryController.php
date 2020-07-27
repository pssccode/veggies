<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use App\Models\SalesHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class HistoryController extends Controller
{
    public function index()
    {
        return view('clients.sales_history.index');
    }

    public function getHistorySelectorsPayloads()
    {
        return [
            'months' => SalesHistory::getDistinctMonths(0, 0, 1),
            'years' => SalesHistory::getDistinctYears(0, 1),
            'cultures' => Culture::getListForSelector()
        ];
    }

    public function getHistoryYearMonths($year)
    {
        return SalesHistory::getDistinctMonths($year, 0, 1);
    }

    public function getSalesHistoryTable(Request $request)
    {
        $sales = SalesHistory::with('culture');
        $cultureId = $request->get('culture');
        $year = $request->get('year');
        $month = $request->get('month');
        $dates = $request->get('date');

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
        $summary = $this->getSummary(clone $sales);
        return DataTables::of($sales)
            ->with([
                'weight' => $summary['weight'],
                'sum' => $summary['sum'],
                'min_price' => $summary['min_price'],
                'max_price' => $summary['max_price'],
                'min_weight' => $summary['min_weight'],
                'max_weight' => $summary['max_weight'],
                'min_sum' => $summary['min_sum'],
                'max_sum' => $summary['max_sum'],
                'picking_cnt' => $summary['picking_cnt'],
                'all_price' => $summary['all_price'],
            ])
            ->editColumn('date', function($item){
                return Carbon::parse($item->date)->format('d.m.Y');
            })
            ->editColumn('culture_name', function($item){
                return isset($item->culture) ? $item->culture->name : '';
            })
            ->make(true);
    }

    public function getSummary($query)
    {
        $q = $query->selectRaw("count(id) as picking_cnt, sum(sum) as all_sum, sum(weight) as all_weight,
        min(price) as min_price, max(price) as max_price,
        min(weight) as min_weight, max(weight) as max_weight,
        min(sum) as min_sum, max(sum) as max_sum, sum(price) as all_price")->first();
        $data = [
            'weight' => 0,
            'sum' => 0,
            'min_price' => 0,
            'max_price' => 0,
            'min_weight' => 0,
            'max_weight' => 0,
            'min_sum' => 0,
            'max_sum' => 0,
            'picking_cnt' => 0,
            'all_price' => 0,
        ];
        if(!isset($q)){
            return $data;
        }
        $data = [
            'sum' => $q->all_sum,
            'weight' => $q->all_weight,
            'min_price' => $q->min_price,
            'max_price' => $q->max_price,
            'min_weight' => $q->min_weight,
            'max_weight' => $q->max_weight,
            'min_sum' => $q->min_sum,
            'max_sum' => $q->max_sum,
            'picking_cnt' => $q->picking_cnt,
            'all_price' => $q->all_price,
        ];
        return $data;
    }

    public function storeSale(Request $request)
    {

        $cultureId = $request->get('culture');
        $date = $request->get('date');

        $items = [];
        $forms = $request->get('forms');

        foreach ($forms as $form) {
            $items[] = [
                'user_id' => 2,
                'culture_id' => $cultureId,
                'price' => $form['price'],
                'weight' => $form['weight'],
                'sum' => $form['sum'],
                'date' => $date,
            ];
        }

        SalesHistory::insert($items);
    }
}
