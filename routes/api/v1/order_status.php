<?php

use App\Http\Controllers\Api\OrderStatusController;

Route::get('/order-statuses', [OrderStatusController::class, 'index']);
Route::get('/order-status/{uuid}', [OrderStatusController::class, 'show']);
