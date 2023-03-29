<?php

use App\Http\Controllers\Api\UserController;

Route::prefix('/user')->group(function () {
    Route::post('/create', [UserController::class, 'createUser']);
    Route::post('/login', [UserController::class, 'loginUser']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/', [UserController::class, 'getUser']);
        Route::get('/orders', [UserController::class, 'getUserOrders']);
    });
});
