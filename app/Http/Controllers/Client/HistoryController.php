<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use App\Models\SalesHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $sales = SalesHistory::query();
        $cultureId = $request->get('culture');
        $year = $request->get('year');
        $month = $request->get('month');
        if($cultureId > 0){
            $sales->where('culture_id', $cultureId);
        }
        if($year > 0){
            $sales->whereRaw("DATE_FORMAT(date, '%Y') = ".$year);
        }
        if($month > 0){
            $sales->whereRaw("DATE_FORMAT(date, '%m') = ".$month);
        }
        return DataTables::of($sales)
            ->editColumn('date', function($item){
                return Carbon::parse($item->date)->format('d.m.Y');
            })
            ->make(true);
    }

    public function storeSale(Request $request)
    {
        $item = new SalesHistory();
        $item->user_id = 1;
        $item->culture_id = $request->get('culture');
        $item->price = $request->get('price');
        $item->weight = $request->get('weight');
        $item->sum = $request->get('sum');
        $item->date = $request->get('date');
        $item->save();
    }
}
