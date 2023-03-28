<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro('success', function ($code = 200, $data = [], $extra = []) {
            return response()->json([
                'success' => 1,
                'data' => $data,
                'error' => null,
                'errors' => [],
                'extra' => $extra,
            ], $code);
        });

        Response::macro('error', function ($code = 500, string $error, $errors = [], $trace = []) {
            return response()->json([
                'success' => 0,
                'data' => [],
                'error' => $error,
                'errors' => $errors,
                'trace' => $trace,
            ], $code);
        });
    }
}
