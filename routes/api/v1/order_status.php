<?php

use App\Http\Controllers\Api\OrderStatusController;

Route::prefix('/order-statuses')->group(function () {
    Route::get('/', [OrderStatusController::class, 'index']);
    Route::get('/{uuid}', [OrderStatusController::class, 'show']);
});
