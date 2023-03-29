<?php

Route::prefix('/v1')->group(function () {
    require_once(__DIR__ . '/admin.php');
    require_once(__DIR__ . '/file.php');
    require_once(__DIR__ . '/main.php');
    require_once(__DIR__ . '/order_status.php');
    require_once(__DIR__ . '/user.php');
});
