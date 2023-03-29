<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\OrderStatusController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\FileController;
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

        Route::post('/file/upload', [FileController::class, 'upload']);
    });

    Route::get('/file/{uuid}', [FileController::class, 'download']);

    Route::get('/order-statuses', [OrderStatusController::class, 'index']);
    Route::get('/order-statuses/{uuid}', [OrderStatusController::class, 'show']);

    Route::post('/admin/login', [AdminController::class, 'loginAdmin']);

    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::post('/admin/create', [AdminController::class, 'createAdmin']);
    });
});
