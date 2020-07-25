<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SalesHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartsController extends Controller
{
    public function index()
    {
        return view('clients.charts.index');
    }

    public function getCharts(Request $request)
    {
        $cultureId = $request->get('culture');
        $year = $request->get('year');
        $month = $request->get('month');
        $dates = $request->get('date');

        $result = SalesHistory::getInfoForCharts($cultureId, $year, $month, $dates);
        return $result;
    }
}
