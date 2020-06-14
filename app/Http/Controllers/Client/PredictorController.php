<?php

namespace App\Http\Controllers\Client;

use App\Helpers\PredictorHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PredictorController extends Controller
{
    public function index()
    {
        $predictor = new PredictorHelper('2020-06-12', 1, 2);
        if(!$predictor){
            return false; // Нет записей про продажи
        }
        $weight = $predictor->getWeightPrediction();
        $price = $predictor->getPricePrediction();

        return view('clients.predictor', [
            'weight' => $weight,
            'price' => $price
        ]);
    }
}
