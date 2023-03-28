<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::post('/user/create', [UserController::class, 'createUser']);
    Route::post('/user/login', [UserController::class, 'loginUser']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/user', [UserController::class, 'getUser']);
        Route::get('/user/orders', [UserController::class, 'getUserOrders']);
    });
});
