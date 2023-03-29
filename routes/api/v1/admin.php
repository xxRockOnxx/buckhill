<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->group(function () {
    Route::post('/login', [AdminController::class, 'loginAdmin']);

    Route::post('/create', [AdminController::class, 'createAdmin'])
        ->middleware(['auth:api', 'admin']);
});
