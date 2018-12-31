<?php

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

Route::get('/joybird', 'JoybirdController@getReport');
Route::get('/report', 'JoybirdController@getSalesByPage');
Route::post('/report', 'JoybirdController@getSalesByPage');
Route::get('/count', 'JoybirdController@getSalesCount');
Route::get('/chart', 'JoybirdController@getChart');
Route::post('/chartdata', 'JoybirdController@getChartData');
