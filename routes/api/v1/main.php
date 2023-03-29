<?php

use App\Http\Controllers\Api\MainController;

Route::prefix('/main')->group(function () {
    Route::get('/blog', [MainController::class, 'blogs']);
    Route::get('/blog/{uuid}', [MainController::class, 'blog']);
    Route::get('/promotions', [MainController::class, 'promotions']);
});
