<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::any('mq', 'TestController@mq');
Route::any('/', function (){
    dd('hello world');
});
Route::any('pay/index', 'PayController@index');
Route::any('pay/notify', 'PayController@notify');
