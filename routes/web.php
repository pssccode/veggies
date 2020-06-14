<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@index')->name('main.page');
Route::namespace('Client')->group(function (){
    Route::group([], function () { // Sales history
        Route::get('/sales_history', 'HistoryController@index');
        Route::get('/get_history_selectors_payloads', 'HistoryController@getHistorySelectorsPayloads')->name('get.history.selectors.payloads');
        Route::get('/get_history_year_months/{year}', 'HistoryController@getHistoryYearMonths')->name('get.history.year.months');
        Route::post('/get_sales_history_table', 'HistoryController@getSalesHistoryTable')->name('get.sales.history.table');
        Route::post('/store_sale', 'HistoryController@storeSale')->name('store.sale');
    });

    Route::group(['as' => 'predictor.', 'prefix' => 'predictor'], function () { // predictor
        Route::get('/index', 'PredictorController@index')->name('index');
    });
});
