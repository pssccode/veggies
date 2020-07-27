<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\SalesHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ComparisonController extends Controller
{
    public function index()
    {
        return view('clients.comparison.index');
    }

    public function getComparison(Request $request)
    {
        $defaultDate = $request->get('date');
        $dates = $request->get('dates');
        $cultureId = $request->get('culture');

        $results = SalesHistory::getCompareTable($defaultDate, $dates, $cultureId);
        return $results;
    }

}
