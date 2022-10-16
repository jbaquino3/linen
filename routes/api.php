<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockRoomController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RequestController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'location'], function () {
    Route::get('/', [LocationController::class, 'index']);
    Route::get('{id}', [LocationController::class, 'read']);
    Route::post('/', [LocationController::class, 'store']);
    Route::put('{id}', [LocationController::class, 'update']);
    Route::delete('{id}', [LocationController::class, 'delete']);
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
});

Route::group(['prefix' => 'stock_room'], function () {
    Route::get('/', [StockRoomController::class, 'index']);
});

Route::group(['prefix' => 'material'], function () {
    Route::get('/', [MaterialController::class, 'index']);
});

Route::group(['prefix' => 'product'], function () {
    Route::get('/', [ProductController::class, 'index']);
});

Route::group(['prefix' => 'transaction'], function () {
    Route::get('/', [TransactionController::class, 'index']);
});

Route::group(['prefix' => 'request'], function () {
    Route::get('/', [RequestController::class, 'index']);
});