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
    Route::get('/sales_history', 'HistoryController@index');
    Route::get('/get_history_selectors_payloads', 'HistoryController@getHistorySelectorsPayloads')->name('get.history.selectors.payloads');
});
