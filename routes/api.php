<?php

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

/**
 * 用户
 */
Route::namespace('User')->prefix('user')->group(function () {

    require_once  __DIR__ . '/api/user.php';
});

/**
 * 商品
 */
Route::namespace('Item')->prefix('item')->group(function () {

    require_once  __DIR__ . '/api/item.php';
});

/**
 * 订单
 */
Route::namespace('Order')->prefix('order')->group(function () {

    require_once  __DIR__ . '/api/order.php';
});

/**
 * 支付
 */
Route::namespace('Payment')->prefix('payment')->group(function () {

    require_once  __DIR__ . '/api/payment.php';
});
