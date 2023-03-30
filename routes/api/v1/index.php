<?php

Route::prefix('/v1')->group(function () {
    require __DIR__ . '/admin.php';
    require __DIR__ . '/file.php';
    require __DIR__ . '/main.php';
    require __DIR__ . '/order_status.php';
    require __DIR__ . '/user.php';
});
