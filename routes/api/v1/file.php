<?php

use App\Http\Controllers\FileController;

Route::prefix('/file')->group(function () {
    Route::get('/{uuid}', [FileController::class, 'download']);

    Route::post('/upload', [FileController::class, 'upload'])
        ->middleware('auth:api');
});
