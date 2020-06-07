<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index()
    {
        return view('clients.sales_history.index');
    }

    public function getHistorySelectorsPayloads()
    {
        return [
            'months' => [
                ['number' => 4, 'name' => 'Апрель'],
                ['number' => 5, 'name' => 'Май'],
                ['number' => 6, 'name' => 'Июнь'],
                ['number' => 7, 'name' => 'Июль'],
                ['number' => 8, 'name' => 'Август'],
                ['number' => 9, 'name' => 'Сентябрь']
            ],
            'years' => [

            ]
        ];
    }
}
