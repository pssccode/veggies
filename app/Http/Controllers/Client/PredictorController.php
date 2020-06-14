<?php

namespace App\Http\Controllers\Client;

use App\Helpers\PredictorHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PredictorController extends Controller
{
    public function index()
    {
        $predictor = new PredictorHelper('2020-14-06', 1, 2);
        $weight = 0;
        $price = 0;
        return view('clients.predictor', [
            'weight' => $weight,
            'price' => $price
        ]);
    }
}
